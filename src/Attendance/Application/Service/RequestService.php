<?php

namespace App\Attendance\Application\Service;

use App\Attendance\Application\Command\ApproveRequestCommand;
use App\Attendance\Application\Command\CreateRequestCommand;
use App\Attendance\Application\Command\DisapproveRequestCommand;
use App\Attendance\Application\Command\MoveToAwaitingResponseCommand;
use App\Attendance\Application\Command\MoveToAwaitingSupportCommand;
use App\Attendance\Application\Command\MoveToCanceledCommand;
use App\Attendance\Application\Command\MoveToInAttendanceCommand;
use App\Attendance\Application\Command\TransferCompanyCommand;
use App\Attendance\Application\Command\UpdateRequestCommand;
use App\Attendance\Application\Exception\RequestNotFoundException;
use App\Attendance\Application\Exception\SectionNotFromCompanyException;
use App\Attendance\Application\Exception\UnauthorizedRequestUpdateException;
use App\Attendance\Application\Exception\UnauthorizedStatusChangeException;
use App\Attendance\Application\Exception\UnauthorizedStatusUpdateException;
use App\Attendance\Application\Exception\UnauthorizedTransferCompanyException;
use App\Attendance\Application\Query\ExportRequestsToPdfQuery;
use App\Attendance\Application\Query\FindRequestByIdQuery;
use App\Attendance\Domain\Entity\Log;
use App\Attendance\Domain\Entity\Request;
use App\Attendance\Domain\Entity\Status;
use App\Attendance\Domain\Repository\RequestRepository;
use App\Attendance\Domain\Repository\StatusRepository;
use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Application\Exception\SectionNotFoundException;
use App\Company\Domain\Repository\CompanyRepository;
use App\Company\Domain\Repository\SectionRepository;
use App\Core\Infrastructure\Storaged\AWS\S3;
use App\User\Application\Exception\InvalidUserPrivileges;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepository;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Ramsey\Uuid\Uuid;

/**
 * Class RequestService
 * @package App\Attendance\Application\Service
 */
class RequestService
{
    /**
     * @var RequestRepository
     */
    private $requestRepository;

    /**
     * @var StatusRepository
     */
    private $statusRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SectionRepository
     */
    private $sectionRepository;

    /**
     * @var S3
     */
    private $s3;

    /**
     * RequestService constructor.
     * @param RequestRepository $requestRepository
     * @param StatusRepository $statusRepository
     * @param CompanyRepository $companyRepository
     * @param UserRepository $userRepository
     * @param SectionRepository $sectionRepository
     * @param S3 $s3
     */
    public function __construct(RequestRepository $requestRepository, StatusRepository $statusRepository, CompanyRepository $companyRepository, UserRepository $userRepository, SectionRepository $sectionRepository, S3 $s3)
    {
        $this->requestRepository = $requestRepository;
        $this->statusRepository = $statusRepository;
        $this->companyRepository = $companyRepository;
        $this->userRepository = $userRepository;
        $this->sectionRepository = $sectionRepository;
        $this->s3 = $s3;
    }

    /**
     * @param CreateRequestCommand $command
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws InvalidUserPrivileges
     * @throws SectionNotFoundException
     * @throws SectionNotFromCompanyException
     * @throws UnauthorizedStatusChangeException
     */
    public function create(CreateRequestCommand $command, User $user): Request
    {
        if ($user->getCompanyId() != $this->companyRepository->getMother()->getId()) {
            throw new InvalidUserPrivileges();
        }

        $status = $this->statusRepository->fromId(Status::awaitingSupport);
        $company = $this->companyRepository->fromId($command->getCompanyId());

        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        $section = $this->sectionRepository->fromName($command->getSection());

        if(is_null($section)){
            throw new  SectionNotFoundException();
        }

        if (!($company->getSections()->contains($section))) {
            throw new SectionNotFromCompanyException();
        }

        $logs = new ArrayCollection();

        $logs->add(new Log(null, 'O chamado foi criado'
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'init'));

        $request = new Request(
            null,
            $status,
            $command->getCompanyId(),
            $command->getTitle(),
            $command->getDescription(),
            $command->getPriority(),
            $command->getSection(),
            null,
            $user->getId(),
            Carbon::now()->timezone('America/Sao_Paulo'),
            Carbon::now()->timezone('America/Sao_Paulo'),
            null,
            $logs
        );

        $request =  $this->requestRepository->create($request);
        return  $this->moveToAwaitingSupport(null,$request, $user);
    }

    /**
     * @param ApproveRequestCommand $command
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws InvalidUserPrivileges
     * @throws RequestNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function approveRequest(ApproveRequestCommand $command, User $user): Request
    {
        $request = $this->findById($command->getRequestId());

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (!self::validationApproveRequest($request, $user)) {
            throw new InvalidUserPrivileges();
        }

        $log = new Log(null, $command->getMessage()
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'message');
        $request->getLogs()->add($log);
        $this->requestRepository->update($request);
        $request = $this->moveToApproved($request, $user);

        return $request;
    }

    /**
     * @param int $id
     * @return Request
     * @throws RequestNotFoundException
     */
    public function findById(int $id)
    {
        $request = $this->requestRepository->fromId($id);

        if (is_null($request)) {
            throw new RequestNotFoundException();
        }

        return $request;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return bool
     */
    private function validationApproveRequest(Request $request, User $user)
    {
        if ($user->getRole() == 'ROLE_MANAGER' && $user->getCompanyId() == 1) {
            return true;
        }

        if ($user->getId() == $request->getRequestedBy()) {
            return true;
        }

        return false;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws InvalidUserPrivileges
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToApproved(Request $request, User $user): Request
    {
        if (
            !($request->getStatus()->getId() == Status::inAttendance) &&
            !($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (!self::validationMoveToApproved($request, $user)) {
            throw new InvalidUserPrivileges();
        }

        $log = new Log(null, 'Chamado aprovado'
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'approve');
        $status = $this->statusRepository->fromId(Status::approved);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return bool
     */
    private function validationMoveToApproved(Request $request, User $user)
    {
        if ($user->getRole() == 'ROLE_MANAGER' && $user->getCompanyId() != 1) {
            if ($request->getAssignedTo() != null) {
                $userAssigned = $this->userRepository->fromId($request->getAssignedTo());
                return $userAssigned->getCompanyId() == $user->getCompanyId();
            }
        }

        if ($user->getId() == $request->getAssignedTo()) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @return array
     * @throws Exception
     */
    public function findAll(User $user)
    {
        switch ($user->getRole()) {
            case 'ROLE_USER':
                $requests = $this->requestRepository->findRequestsToSupport($user);
                break;
            case 'ROLE_MANAGER':
                $requests = $this->requestRepository->findRequestsToManager($user);
                break;
            case 'ROLE_CLIENT':
                $requests = $this->requestRepository->findRequestsToClient($user);
                break;
            default:
                $requests = [];
        }

        /**
         * @var $request Request
         */
        foreach ($requests as $request) {
            $request->setSla(self::calculateSla($request));
        }

        return $requests;
    }

    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public static function calculateSla(Request $request): string
    {
        // Separate important logs for sla count
        $importantLogs = new ArrayCollection();

        foreach ($request->getLogs()->getValues() as $log) {
            switch ($log->getCommand()) {
                case 'awaitingSupport':
                case 'init':
                    $importantLogs->add(['command' => 'start', 'datetime' => $log->getCreatedAt()]);
                    break;

                case 'cancel':
                case 'finish':
                case 'approve':
                case 'awaitingResponse':
                    $importantLogs->add(['command' => 'stop', 'datetime' => $log->getCreatedAt()]);
                    break;

                default:
                    break;
            }
        }

        // Separate logs into intervals
        $pairs = new ArrayCollection();
        $lastCommand = null;

        foreach ($importantLogs as $log) {
            if ($log['command'] == $lastCommand) {
                break;
            }

            $pairs->add($log);
            $lastCommand = $log['command'];
        }


        // Creates a stop if it is still counting
        $lastLog = $pairs->last();

        if ($lastLog['command'] == 'start') {
            $now = Carbon::now()->timezone('America/Sao_Paulo')->toDateTime();
            $pairs->add(['command' => 'stop', 'datetime' => $now]);
        }

        // Create datetime intervals
        $intervals = new ArrayCollection();
        $totalOfIntervals = $pairs->count() / 2;
        $pairs = $pairs->toArray();

        for ($i = 0; $i <= $totalOfIntervals; $i += 2) {
            $start = new Carbon($pairs[$i]['datetime']);
            $stop = new Carbon($pairs[$i + 1]['datetime']);

            $intervals->add($start->diff($stop));
        }

        // Loop the intervals
        $sla = CarbonInterval::hours(0);

        foreach ($intervals as $interval) {
            $interval = new CarbonInterval($interval);
            $sla->add($interval);
        }

        return $sla->format('%hh %im');
    }

    /**
     * @param DisapproveRequestCommand|null $command
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws InvalidUserPrivileges
     * @throws RequestNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function disapproveRequest(?DisapproveRequestCommand $command, User $user): Request
    {
        $request = $this->findById($command->getRequestId());

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (!self::validationDisapproveRequest($request, $user)) {
            throw new InvalidUserPrivileges();
        }

        if(is_null($command)){
            $command = DisapproveRequestCommand::fromArray([]);
            $command->setMessage("");
        }

        $log = new Log(null, $command->getMessage()
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'message');
        $request->getLogs()->add($log);
        $this->requestRepository->update($request);
        $request = $this->moveToAwaitingSupport(null, $request, $user);

        return $request;
    }

    /**
     * @param Request $request
     * @param User $user
     * @return bool
     */
    private function validationDisapproveRequest(Request $request, User $user)
    {
        if ($user->getRole() == 'ROLE_MANAGER' && $user->getCompanyId() == 1) {
            return true;
        }

        if ($user->getId() == $request->getRequestedBy()) {
            return true;
        }

        return false;
    }

    /**
     * @param MoveToAwaitingSupportCommand|null $command
     * @param Request $request
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws InvalidUserPrivileges
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToAwaitingSupport(?MoveToAwaitingSupportCommand $command, Request $request, User $user): Request
    {
        if (
            !($request->getStatus()->getId() == Status::inAttendance) &&
            !($request->getStatus()->getId() == Status::awaitingSupport) &&
            !($request->getStatus()->getId() == Status::awaitingResponse) &&
            !($request->getStatus()->getId() == Status::approved)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (!self::validationMoveToAwaitingSupport($request, $user)) {
            throw new InvalidUserPrivileges();
        }

        if(is_null($command)){
            $command = MoveToAwaitingSupportCommand::fromArray([]);
            $command->setMessage("");
        }

        $log = new Log(null, 'Chamado aguardando atendimento'
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            . ' <br> mensagem: ' . $command->getMessage()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'awaitingSupport');
        $status = $this->statusRepository->fromId(Status::awaitingSupport);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));
        $request->setAssignedTo(null);
        return $this->requestRepository->update($request);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return bool
     */
    private function validationMoveToAwaitingSupport(Request $request, User $user)
    {
        if ($user->getRole() == 'ROLE_MANAGER' && $user->getCompanyId() == 1) {
            return true;
        }

        if ($user->getRole() == 'ROLE_MANAGER' && $user->getCompanyId() != 1) {
            if ($request->getAssignedTo() != null) {
                $userAssigned = $this->userRepository->fromId($request->getAssignedTo());
                return $userAssigned->getCompanyId() == $user->getCompanyId();
            }
        }

        if ($user->getId() == $request->getRequestedBy()) {
            return true;
        }

        return false;
    }

    /**
     * @param MoveToInAttendanceCommand|null $command
     * @param Request $request
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws InvalidUserPrivileges
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToInAttendance(?MoveToInAttendanceCommand $command, Request $request, User $user): Request
    {
        if (
            !($request->getStatus()->getId() == Status::awaitingSupport) &&
            !($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (self::validationMoveToInAttendance($request, $user)) {
            throw new InvalidUserPrivileges();
        }

        if(is_null($command)){
            $command = MoveToInAttendanceCommand::fromArray([]);
            $command->setMessage("");
        }

        $log = new Log(null, 'Chamado em atendimento'
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            . ' <br> mensagem: ' . $command->getMessage()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'inAttendance');
        $status = $this->statusRepository->fromId(Status::inAttendance);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));
        $request->setAssignedTo($user->getId());

        return $this->requestRepository->update($request);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return bool
     */
    private function validationMoveToInAttendance(Request $request, User $user)
    {
        if ($user->getRole() == 'ROLE_MANAGER' && $user->getCompanyId() == 1) {
            return true;
        }

        if ($user->getId() == $request->getRequestedBy()) {
            return true;
        }

        return false;
    }

    /**
     * @param MoveToAwaitingResponseCommand|null $command
     * @param Request $request
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws InvalidUserPrivileges
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToAwaitingResponse(?MoveToAwaitingResponseCommand  $command, Request $request, User $user): Request
    {
        if (!($request->getStatus()->getId() == Status::inAttendance)) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (!self::validateMoveToAwaitingResponse($request, $user)) {
            throw new InvalidUserPrivileges();
        }

        if(is_null($command)){
            $command = MoveToAwaitingResponseCommand::fromArray([]);
            $command->setMessage("");
        }

        $log = new Log(null, 'Chamado aguardando resposta'
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            . ' <br> mensagem: ' . $command->getMessage()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'awaitingResponse');
        $status = $this->statusRepository->fromId(Status::awaitingResponse);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    private function validateMoveToAwaitingResponse(Request $request, User $user)
    {
        if ($user->getId() == $request->getAssignedTo()) {
            return true;
        }

        if (($user->getRole() == 'ROLE_MANAGER') && (!is_null($request->getAssignedTo()))) {
            $userAssigned = $this->userRepository->fromId($request->getAssignedTo());
            return $user->getCompanyId() == $userAssigned->getCompanyId();
        }

        return false;

    }

    /**
     * @param Request $request
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToFinished(Request $request, User $user): Request
    {

        if (!($request->getStatus()->getId() == Status::approved)) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }


        $log = new Log(null, 'Chamado finalizado'
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'finish');
        $status = $this->statusRepository->fromId(Status::finished);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    /**
     * @param MoveToCanceledCommand|null $command
     * @param Request $request
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws InvalidUserPrivileges
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToCanceled(?MoveToCanceledCommand $command, Request $request, User $user): Request
    {
        if (
            !($request->getStatus()->getId() == Status::awaitingSupport) &&
            !($request->getStatus()->getId() == Status::inAttendance) &&
            !($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (!self::validationMoveToCanceled($request, $user)) {
            throw new InvalidUserPrivileges();
        }

        if(is_null($command)){
            $command = MoveToCanceledCommand::fromArray([]);
            $command->setMessage("");
        }

        $log = new Log(null, 'Chamado cancelado'
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            . ' <br> mensagem: ' . $command->getMessage()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'cancel');
        $status = $this->statusRepository->fromId(Status::canceled);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return bool
     */
    private function validationMoveToCanceled(Request $request, User $user)
    {
        if ($user->getRole() == 'ROLE_MANAGER' && $user->getCompanyId() == 1) {
            return true;
        }

        if ($user->getRole() == 'ROLE_MANAGER' && $user->getCompanyId() != 1) {
            if ($request->getAssignedTo() != null) {
                $userAssigned = $this->userRepository->fromId($request->getAssignedTo());
                return $userAssigned->getCompanyId() == $user->getCompanyId();
            }
        }

        if ($user->getId() == $request->getRequestedBy()) {
            return true;
        }

        return false;
    }

    /**
     * @param UpdateRequestCommand|null $command
     * @param User $user
     * @return Request|null
     * @throws CompanyNotFoundException
     * @throws RequestNotFoundException
     * @throws UnauthorizedRequestUpdateException
     * @throws UnauthorizedStatusUpdateException
     */
    public function update(?UpdateRequestCommand $command, User $user)
    {
        $request = $this->findById($command->getId());

        if (
            !($request->getStatus()->getId() == Status::awaitingResponse) &&
            !($request->getStatus()->getId() == Status::awaitingSupport)
        ) {
            throw new UnauthorizedStatusUpdateException();
        }

        if ($user->getId() != $request->getRequestedBy()) {
            throw new UnauthorizedRequestUpdateException();
        }

        if (!is_null($command->getTitle())) {
            $request->setTitle($command->getTitle());
        }

        if (!is_null($command->getDescription())) {
            $request->setDescription($command->getDescription());
        }

        if (!is_null($command->getPriority())) {
            $request->setPriority($command->getPriority());
        }

        $statusName = $request->getStatus()->getId() == Status::awaitingSupport ? 'awaitingSupport' : 'awaitingResponse';

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        $log = new Log(null, 'Chamado alterado'
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), $statusName);

        $request->getLogs()->add($log);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    /**
     * @param TransferCompanyCommand|null $command
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws InvalidUserPrivileges
     * @throws RequestNotFoundException
     * @throws SectionNotFoundException
     * @throws UnauthorizedStatusChangeException
     * @throws UnauthorizedTransferCompanyException
     */
    public function transferCompany(?TransferCompanyCommand $command, User $user)
    {
        $request = $this->findById($command->getRequestId());

        if (
            !($request->getStatus()->getId() == Status::awaitingSupport) &&
            !($request->getStatus()->getId() == Status::inAttendance) &&
            !($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $company = $this->companyRepository->fromId($command->getCompanyId());

        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        $section = $this->sectionRepository->fromName($command->getSection());

        if (is_null($section)) {
            throw new SectionNotFoundException();
        }

        if (!($company->getSections()->contains($section))) {
            throw new UnauthorizedTransferCompanyException();
        }

        $request->setSection($section->getName());
        $request->setCompanyId($company->getId());
        $request->setAssignedTo(null);

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (!self::validationTransferCompany($request, $user)) {
            throw new InvalidUserPrivileges();
        }

        if(is_null($command)){
            $command = TransferCompanyCommand::fromArray([]);
            $command->setMessage("");
        }

        $log = new Log(null, 'Chamado transferido'
            . ' por : ' . $user->getName()
            . ' <br> trabalha em: ' . $companyUser->getName()
            . ' <br> mensagem : ' . $command->getMessage()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'transfer');

        $request->getLogs()->add($log);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));
        $request->setAssignedTo(null);

        $request = $this->requestRepository->update($request);
        $request = $this->moveToAwaitingSupport(null, $request, $user);

        return $request;
    }

    private function validationTransferCompany(Request $request, User $user)
    {
        if (($user->getRole() == 'ROLE_MANAGER') && (!is_null($request->getAssignedTo()))) {
            $userAssigned = $this->userRepository->fromId($request->getAssignedTo());
            return $user->getCompanyId() == $userAssigned->getCompanyId();
        }

        return false;
    }

    /**
     * @param ExportRequestsToPdfQuery $query
     * @return array
     * @throws MpdfException
     * @throws Exception
     */
    public function ExportsRequestsToPdf(ExportRequestsToPdfQuery $query)
    {
        $result = $this->requestRepository->searchRequests(
            $query->getTitle(),
            $query->getInitialDate(),
            $query->getFinalDate(),
            $query->getStatusId(),
            $query->getAssignedTo(),
            $query->getRequestedBy()
        );

        if ($result == []) {
            return [];
        }

        $mpdf = new Mpdf();
        $uuid = Uuid::uuid4();
        $mpdf->WriteHTML('<h1 style="text-align: center">CALLR2 PDF</h1>');
        $mpdf->WriteHTML('<table style="width:100%;" >');
        $mpdf->WriteHTML('<tr>');
        $mpdf->WriteHTML('<th style="text-align: left">Title</th>');
        $mpdf->WriteHTML('<th style="text-align: left">Priority</th>');
        $mpdf->WriteHTML('<th style="text-align: left">section</th>');
        $mpdf->WriteHTML('<th style="text-align: left">Last update</th>');
        $mpdf->WriteHTML('</tr>');
        foreach ($result as $r) {
            $mpdf->WriteHTML('<tr><td>' . $r->getTitle() . '</td><td>' . $r->getPriority() . '</td><td>' . $r->getSection() . '</td><td>' . new Carbon($r->getUpdatedAt()) . '</td></tr>');
        }
        $mpdf->WriteHTML('</table>');
        $uuid = $uuid->serialize();
        $mpdf->Output($uuid . '.pdf', Destination::FILE);
        $url = $this->s3->sendFile('request', $uuid, $uuid . '.pdf', 'requestsFile.pdf', 'application/pdf');
        return ["url" => $url];
    }

    /**
     * @param FindRequestByIdQuery $query
     * @param User $user
     * @return Request
     * @throws InvalidUserPrivileges
     * @throws RequestNotFoundException
     */
    public function fromId(FindRequestByIdQuery $query, User $user)
    {
        $request = $this->requestRepository->fromId($query->getId());

        if (is_null($request)) {
            throw new RequestNotFoundException();
        }

        if (!self::validationFromId($request, $user)) {
            throw new InvalidUserPrivileges();
        }

        if($request->getCompanyId() != $user->getCompanyId()){
            throw new InvalidUserPrivileges();
        }

        return $request;
    }


    private function validationFromId(Request $request, User $user)
    {
        if($request->getCompanyId() != $user->getCompanyId()){
            return (($request->getRequestedBy() == $user->getId())
                || (($user->getRole() == 'ROLE_MANAGER' && $user->getCompanyId() == $this->companyRepository->getMother()->getId())));
        }
        return true;
    }
}