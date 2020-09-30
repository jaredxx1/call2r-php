<?php

namespace App\Attendance\Application\Service;

use App\Attendance\Application\Command\AnsweredRequestActionCommand;
use App\Attendance\Application\Command\ApproveRequestCommand;
use App\Attendance\Application\Command\CreateRequestCommand;
use App\Attendance\Application\Command\DisapproveRequestCommand;
use App\Attendance\Application\Command\MoveToAwaitingResponseCommand;
use App\Attendance\Application\Command\MoveToCanceledCommand;
use App\Attendance\Application\Command\MoveToInAttendanceCommand;
use App\Attendance\Application\Command\RequestLogCommand;
use App\Attendance\Application\Command\SubmitForApprovalCommand;
use App\Attendance\Application\Command\TransferCompanyCommand;
use App\Attendance\Application\Command\UpdateRequestCommand;
use App\Attendance\Application\Exception\RequestNotFoundException;
use App\Attendance\Application\Exception\SectionNotFromCompanyException;
use App\Attendance\Application\Exception\UnauthorizedDisapproveRequestException;
use App\Attendance\Application\Exception\UnauthorizedMoveToInAttendanceException;
use App\Attendance\Application\Exception\UnauthorizedTransferCompanyException;
use App\Attendance\Application\Exception\UnauthorizedRequestException;
use App\Attendance\Application\Exception\UnauthorizedRequestUpdateException;
use App\Attendance\Application\Exception\UnauthorizedStatusChangeException;
use App\Attendance\Application\Exception\UnauthorizedStatusUpdateException;
use App\Attendance\Application\Query\ExportRequestsToPdfQuery;
use App\Attendance\Application\Query\FindRequestByIdQuery;
use App\Attendance\Application\Query\FindRequestsQuery;
use App\Attendance\Domain\Entity\Log;
use App\Attendance\Domain\Entity\Request;
use App\Attendance\Domain\Entity\Status;
use App\Attendance\Domain\Repository\RequestRepository;
use App\Attendance\Domain\Repository\StatusRepository;
use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Application\Exception\SectionNotFoundException;
use App\Company\Domain\Entity\Company;
use App\Company\Domain\Repository\CompanyRepository;
use App\Company\Domain\Repository\SectionRepository;
use App\Core\Infrastructure\Storaged\AWS\S3;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepository;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @throws SectionNotFoundException
     * @throws SectionNotFromCompanyException
     * @throws UnauthorizedStatusChangeException
     */
    public function create(CreateRequestCommand $command, User $user): Request
    {

        $status = $this->statusRepository->fromId(Status::awaitingSupport);
        $company = $this->companyRepository->fromId($command->getCompanyId());

        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        $section = $this->sectionRepository->fromId($command->getSectionId());

        if (is_null($section)) {
            throw new  SectionNotFoundException();
        }

        if ($company->getSections()->contains($section) == false) {
            throw new SectionNotFromCompanyException();
        }

        $logs = new ArrayCollection();

        $logs->add(new Log(null, 'O chamado foi criado'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em: ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), Log::init));
        $request = new Request(
            null,
            $status,
            $command->getCompanyId(),
            $command->getTitle(),
            $command->getDescription(),
            $command->getPriority(),
            $section->getName(),
            null,
            $user->getId(),
            Carbon::now()->timezone('America/Sao_Paulo'),
            Carbon::now()->timezone('America/Sao_Paulo'),
            null,
            $logs
        );

        $request = $this->requestRepository->create($request);
        return $this->moveToAwaitingSupport($request, $user);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToAwaitingSupport(Request $request, User $user): Request
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

        $log = new Log(null, 'Chamado esta em aguardando atendimento'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em: ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), Log::awaitingSupport);
        $status = $this->statusRepository->fromId(Status::awaitingSupport);
        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));
        $request->setAssignedTo(null);
        return $this->requestRepository->update($request);
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
     * @param ApproveRequestCommand $command
     * @param Request $request
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToApproved(ApproveRequestCommand $command, Request $request, User $user): Request
    {
        if (
            !($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (is_null($command)) {
            $command = ApproveRequestCommand::fromArray([]);
            $command->setMessage("");
        }

        $log = new Log(null, 'Chamado aprovado'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em : ' . $companyUser->getName()
            . ' <br> Mensagem : ' . $command->getMessage()
            , Carbon::now()->timezone('America/Sao_Paulo'), Log::approve);
        $status = $this->statusRepository->fromId(Status::approved);
        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    /**
     * @param FindRequestsQuery $query
     * @param User $user
     * @return array
     * @throws Exception
     */
    public function findAll(FindRequestsQuery $query, User $user)
    {

        switch ($user->getRole()) {
            case User::client:
                $requests = $this->requestRepository->findRequestsClient
                (
                    $query->getAwaitingSupport(),
                    $query->getInAttendance(),
                    $query->getAwaitingResponse(),
                    $query->getCanceled(),
                    $query->getApproved(),
                    $query->getActive(),
                    $user
                );
                break;
            case User::support:
                $requests = $this->requestRepository->findRequestsSupport
                (
                    $query->getAwaitingSupport(),
                    $query->getInAttendance(),
                    $query->getAwaitingResponse(),
                    $query->getCanceled(),
                    $query->getApproved(),
                    $query->getActive(),
                    $user
                );
                break;
            case User::managerClient:
                $requests = $this->requestRepository->findRequestsManagerClient
                (
                    $query->getAwaitingSupport(),
                    $query->getInAttendance(),
                    $query->getAwaitingResponse(),
                    $query->getCanceled(),
                    $query->getApproved(),
                    $query->getActive()
                );
                break;
            case User::managerSupport:
                $requests = $this->requestRepository->findRequestsManagerSupport
                (
                    $query->getAwaitingSupport(),
                    $query->getInAttendance(),
                    $query->getAwaitingResponse(),
                    $query->getCanceled(),
                    $query->getApproved(),
                    $query->getActive(),
                    $user
                );
                break;
            default:
                $requests = [];
        }
        //dd($requests);
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
    public function calculateSla(Request $request): string
    {

        // Separate important logs for sla count
        $importantLogs = new ArrayCollection();
        foreach ($request->getLogs()->getValues() as $log) {
            switch ($log->getCommand()) {
                case Log::init:
                    $importantLogs->add(['command' => 'start', 'datetime' => $log->getCreatedAt()]);
                    break;
                case Log::awaitingResponse:
                    $importantLogs->add(['command' => 'stop', 'datetime' => $log->getCreatedAt()]);
                    break;
                default:
                    break;
            }
        }
        $last = $importantLogs->last();

        if($last['command'] == 'stop'){
            if($this->verifyResponseTime($last, $request)){
                return '0';
            }
        }
        $pairs = new ArrayCollection();
        $lastCommand = null;

        foreach ($importantLogs as $log) {
            if ($log['command'] == $lastCommand) {
                break;
            }
            $pairs->add($log);
            $lastCommand = $log['command'];
        }

        $lastLog = $pairs->last();

        if ($lastLog['command'] == 'start') {
            $now = Carbon::now()->timezone('America/Sao_Paulo');
            $pairs->add(['command' => 'stop', 'datetime' => (new DateTime($now))]);
        }

        $pairs = $pairs->toArray();

        $start = new Carbon($pairs[0]['datetime']);
        $stop = new Carbon($pairs[1]['datetime']);

        $interval = ($start->addHour($request->getPriority()))->diff($stop);

        return $interval->format('%R %dd %hh %im');
    }

    /**
     * @param array $last
     * @param Request $request
     * @throws Exception
     */
    private function verifyResponseTime(array $last, Request $request)
    {
        $now = Carbon::now()->timezone('America/Sao_Paulo');
        $last = new Carbon($last['datetime']);
        $interval = ($last)->diff($now);
        if($interval->d >= 7){
            $log = new Log(null, 'Chamado finalizado por falta de resposta por parte do usuário'
                , Carbon::now()->timezone('America/Sao_Paulo'), Log::approve);
            $status = $this->statusRepository->fromId(Status::approved);
            $request->getLogs()->add($log);
            $request->setStatus($status);
            $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));
            $this->requestRepository->update($request);
            return true;
        }
        return false;
    }

    /**
     * @param DisapproveRequestCommand|null $command
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws RequestNotFoundException
     * @throws UnauthorizedStatusChangeException
     * @throws UnauthorizedDisapproveRequestException
     */
    public function disapproveRequest(?DisapproveRequestCommand $command, User $user): Request
    {
        $request = $this->findById($command->getRequestId());

        if (
        !($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (is_null($command)) {
            $command = DisapproveRequestCommand::fromArray([]);
            $command->setMessage("");
        }

        $log = new Log(null, 'Chamado reprovado'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em : ' . $companyUser->getName()
            . ' <br> Mensagem : ' . $command->getMessage()
            , Carbon::now()->timezone('America/Sao_Paulo'), Log::disapprove);
        $status = $this->statusRepository->fromId(Status::inAttendance);
        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    /**
     * @param MoveToInAttendanceCommand|null $command
     * @param Request $request
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
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
        $message = "";

        if (!is_null($command)) {
            $message = $command->getMessage();
        }

        $log = new Log(null, 'Chamado em atendimento'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em : ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), Log::inAttendance);
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
     * @return Request
     * @throws CompanyNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToFinished(Request $request, User $user): Request
    {

        if (!($request->getStatus()->getId() == Status::approved)
            && !($request->getStatus()->getId() == Status::awaitingResponse)) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        $log = new Log(null, 'Chamado finalizado'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em: ' . $companyUser->getName()
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
     * @throws UnauthorizedStatusChangeException
     * @throws UnauthorizedMoveToInAttendanceException
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

        if (is_null($command)) {
            $command = MoveToCanceledCommand::fromArray([]);
            $command->setMessage("");
        }

        $log = new Log(null, 'Chamado cancelado'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em : ' . $companyUser->getName()
            . ' <br> Mensagem : ' . $command->getMessage()
            , Carbon::now()->timezone('America/Sao_Paulo'), Log::cancel);
        $status = $this->statusRepository->fromId(Status::canceled);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
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
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em: ' . $companyUser->getName()
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
     * @throws RequestNotFoundException
     * @throws SectionNotFoundException
     * @throws SectionNotFromCompanyException
     * @throws UnauthorizedStatusChangeException
     * @throws UnauthorizedTransferCompanyException
     */
    public function transferCompany(?TransferCompanyCommand $command, User $user)
    {
        $request = $this->findById($command->getRequestId());

        if (
            !($request->getStatus()->getId() == Status::awaitingSupport) &&
            !($request->getStatus()->getId() == Status::inAttendance)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $company = $this->companyRepository->fromId($command->getCompanyId());

        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        $section = $this->sectionRepository->fromId($command->getSectionId());

        if (is_null($section)) {
            throw new SectionNotFoundException();
        }

        if (!($company->getSections()->contains($section))) {
            throw new SectionNotFromCompanyException();
        }

        $request->setSection($section->getName());
        $request->setCompanyId($company->getId());
        $request->setAssignedTo(null);

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        if (is_null($command)) {
            $command = TransferCompanyCommand::fromArray([]);
            $command->setMessage("");
        }

        $log = new Log(null, 'Chamado transferido'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em : ' . $companyUser->getName()
            . ' <br> Mensagem : ' . $command->getMessage()
            , Carbon::now()->timezone('America/Sao_Paulo'), Log::awaitingSupport);
        $status = $this->statusRepository->fromId(Status::awaitingSupport);
        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    /**
     * @param ExportRequestsToPdfQuery $query
     * @param User $user
     * @return array
     * @throws MpdfException
     * @throws Exception
     */
    public function ExportsRequestsToPdf(ExportRequestsToPdfQuery $query, User $user)
    {
        $result = [];

        if ($user->getRole() == User::managerSupport) {
            $result = $this->requestRepository->searchRequests(
                $query->getTitle(),
                $query->getInitialDate(),
                $query->getFinalDate(),
                $query->getStatusId(),
                $query->getAssignedTo(),
                $query->getRequestedBy(),
                $user->getCompanyId()
            );
        }

        if ($user->getRole() == User::managerClient) {
            $result = $this->requestRepository->searchRequests(
                $query->getTitle(),
                $query->getInitialDate(),
                $query->getFinalDate(),
                $query->getStatusId(),
                $query->getAssignedTo(),
                $query->getRequestedBy(),
                null
            );
        }

        if ($result == []) {
            return [];
        }

        $mpdf = new Mpdf();
        $uuid = Uuid::uuid4();
        $mpdf->WriteHTML('<h1 style="text-align: center">CALL2R PDF</h1>');
        $mpdf->WriteHTML('<table style="width:100%;" >');
        $mpdf->WriteHTML('<tr>');
        $mpdf->WriteHTML('<th style="text-align: left">Título</th>');
        $mpdf->WriteHTML('<th style="text-align: left">Prioridade</th>');
        $mpdf->WriteHTML('<th style="text-align: left">Área</th>');
        $mpdf->WriteHTML('<th style="text-align: left">Última atualização</th>');
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
     * @return Request|array
     * @throws RequestNotFoundException
     * @throws UnauthorizedRequestException
     * @throws Exception
     */
    public function fromId(FindRequestByIdQuery $query, User $user)
    {
        $request = $this->findById($query->getId());

        if (is_null($request)) {
            throw new RequestNotFoundException();
        }

        switch ($user->getRole()) {
            case User::client:
                if (!($request->getRequestedBy() == $user->getId())) {
                    throw new UnauthorizedRequestException();
                }
                break;
            case User::support:
                if (!(
                    ($request->getCompanyId() == $user->getCompanyId()) &&
                    (($request->getAssignedTo() == $user->getId()) || ($request->getAssignedTo() == null))
                )) {
                    throw new UnauthorizedRequestException();
                }
                break;
            case User::managerSupport:
                if (!($request->getCompanyId() == $user->getCompanyId())) {
                    throw new UnauthorizedRequestException();
                }
                break;
            case User::managerClient:
                break;
            default:
                $request = [];
        }
        $request->setSla(self::calculateSla($request));
        return $request;
    }

    /**
     * @param AnsweredRequestActionCommand $command
     * @param Request $request
     * @param User $user
     * @return Request|null
     * @throws CompanyNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function AnsweredRequest(AnsweredRequestActionCommand $command, Request $request, User $user)
    {
        if (!($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        $log = new Log(null, 'Chamado respondido'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em : ' . $companyUser->getName()
            . ' <br> Mensagem : ' . $command->getMessage()
            , Carbon::now()->timezone('America/Sao_Paulo'), Log::inAttendance);
        $status = $this->statusRepository->fromId(Status::inAttendance);
        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    /**
     * @param User $user
     * @param Company $companyUser
     * @param Request $request
     * @return Request
     */
    public function moveToInAttendanceWithoutValidation(User $user, Company $companyUser, Request $request): Request
    {

        $log = new Log(null, 'Chamado em atendimento'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em : ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'inAttendance');
        $status = $this->statusRepository->fromId(Status::inAttendance);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));
        $request->setAssignedTo($user->getId());

        return $this->requestRepository->update($request);
    }

    /**
     * @param SubmitForApprovalCommand $command
     * @param Request $request
     * @param User $user
     * @return Request|null
     * @throws CompanyNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function submitForApproval(SubmitForApprovalCommand $command, Request $request, User $user)
    {
        if (!($request->getStatus()->getId() == Status::inAttendance)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        $log = new Log(null, 'Chamado aprovado pelo suporte'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em: ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), Log::awaitingResponse);

        $status = $this->statusRepository->fromId(Status::awaitingResponse);
        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    /**
     * @param MoveToAwaitingResponseCommand|null $command
     * @param Request $request
     * @param User $user
     * @return Request
     * @throws CompanyNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToAwaitingResponse(?MoveToAwaitingResponseCommand $command, Request $request, User $user): Request
    {
        if (!($request->getStatus()->getId() == Status::inAttendance)) {
            throw new UnauthorizedStatusChangeException();
        }

        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        $message = "";

        if (!is_null($command)) {
            $message = $command->getMessage();
        }

        $log = new Log(null, 'Chamado aguardando resposta'
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em : ' . $companyUser->getName()
            . ' <br> Mensagem : ' . $message
            , Carbon::now()->timezone('America/Sao_Paulo'), Log::awaitingResponse);
        $status = $this->statusRepository->fromId(Status::awaitingResponse);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));

        return $this->requestRepository->update($request);
    }

    /**
     * @param RequestLogCommand $command
     * @param Request $request
     * @param UserInterface $user
     * @return Request|null
     * @throws CompanyNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function addLogToRequest(RequestLogCommand $command, Request $request, UserInterface $user)
    {
        if (
            !($request->getStatus()->getId() == Status::awaitingResponse) &&
            !($request->getStatus()->getId() == Status::awaitingSupport) &&
            !($request->getStatus()->getId() == Status::inAttendance)
        ) {
            throw new UnauthorizedStatusChangeException();
        }
        $companyUser = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($companyUser)) {
            throw new CompanyNotFoundException();
        }

        $message = "";

        if (!is_null($command)) {
            $message = $command->getMessage();
        }

        $log = new Log(null, $message
            . ' <br><br> Por : ' . $user->getName()
            . ' <br> Trabalha em : ' . $companyUser->getName()
            , Carbon::now()->timezone('America/Sao_Paulo'), 'addLog');
        $status = $this->statusRepository->fromId($request->getStatus()->getId());

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now()->timezone('America/Sao_Paulo'));
        return $this->requestRepository->update($request);
    }
}