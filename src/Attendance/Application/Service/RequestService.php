<?php


namespace App\Attendance\Application\Service;

use App\Attendance\Application\Command\ApproveRequestCommand;
use App\Attendance\Application\Command\CreateRequestCommand;
use App\Attendance\Application\Command\DisapproveRequestCommand;
use App\Attendance\Application\Command\UpdateRequestCommand;
use App\Attendance\Application\Command\TransferCompanyCommand
use App\Attendance\Application\Exception\RequestNotFoundException;
use App\Attendance\Application\Exception\StatusNotFoundException;
use App\Attendance\Application\Exception\UnauthorizedStatusChangeException;
use App\Attendance\Application\Exception\UnauthorizedTransferCompanyException;
use App\Attendance\Application\Exception\UnauthorizedStatusUpdateException;
use App\Attendance\Application\Query\CreatePdfQuery;
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
use App\Security\Domain\Entity\User;
use App\Security\Domain\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Mpdf\Mpdf;
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
     * @return Request|null
     * @throws Exception
     */
    public function create(CreateRequestCommand $command): Request
    {
        $status = $this->statusRepository->fromId(Status::awaitingSupport);
        $company = $this->companyRepository->fromId($command->getCompanyId());

        if (is_null($company)) {
            throw new CompanyNotFoundException();
        }

        $logs = new ArrayCollection();

        $logs->add(new Log(null, 'O chamado foi criado.', Carbon::now(), 'init'));

        $request = new Request(
            null,
            $status,
            $command->getCompanyId(),
            $command->getTitle(),
            $command->getDescription(),
            $command->getPriority(),
            $command->getSection(),
            null,
            null,
            Carbon::now(),
            Carbon::now(),
            null,
            $logs
        );

        return $this->requestRepository->create($request);
    }

    /**
     * @param ApproveRequestCommand $command
     * @return Request
     * @throws RequestNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function approveRequest(ApproveRequestCommand $command): Request
    {
        $request = $this->findById($command->getRequestId());
        $log = new Log(null, $command->getMessage(), Carbon::now(), 'message');
        $request->getLogs()->add($log);
        $this->requestRepository->update($request);
        $request = $this->moveToApproved($request);

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
     * @param User $user
     * @return array
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

        return $requests;
    }

    /**
     * @param Request $request
     * @return Request
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToApproved(Request $request): Request
    {
        if (
            !($request->getStatus()->getId() == Status::inAttendance) &&
            !($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $log = new Log(null, 'Chamado aprovado.', Carbon::now(), 'approve');
        $status = $this->statusRepository->fromId(Status::approved);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now());

        return $this->requestRepository->update($request);
    }

    /**
     * @param DisapproveRequestCommand $command
     * @return Request
     * @throws RequestNotFoundException
     * @throws UnauthorizedStatusChangeException
     */
    public function disapproveRequest(DisapproveRequestCommand $command): Request
    {
        $request = $this->findById($command->getRequestId());
        $log = new Log(null, $command->getMessage(), Carbon::now(), 'message');
        $request->getLogs()->add($log);
        $this->requestRepository->update($request);
        $request = $this->moveToAwaitingSupport($request);

        return $request;
    }

    /**
     * @param Request $request
     * @return Request
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToAwaitingSupport(Request $request): Request
    {
        if (
            !($request->getStatus()->getId() == Status::inAttendance) &&
            !($request->getStatus()->getId() == Status::awaitingSupport) &&
            !($request->getStatus()->getId() == Status::approved)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $log = new Log(null, 'Chamado aguardando atendimento.', Carbon::now(), 'awaitingSupport');
        $status = $this->statusRepository->fromId(Status::awaitingSupport);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now());

        return $this->requestRepository->update($request);
    }

    /**
     * @param Request $request
     * @return Request
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToInAttendance(Request $request): Request
    {
        if (
            !($request->getStatus()->getId() == Status::awaitingSupport) &&
            !($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $log = new Log(null, 'Chamado em atendimento.', Carbon::now(), 'inAttendance');
        $status = $this->statusRepository->fromId(Status::inAttendance);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now());

        return $this->requestRepository->update($request);
    }

    /**
     * @param Request $request
     * @return Request
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToAwaitingResponse(Request $request): Request
    {
        if (!($request->getStatus()->getId() == Status::inAttendance)) {
            throw new UnauthorizedStatusChangeException();
        }

        $log = new Log(null, 'Chamado aguardando resposta.', Carbon::now(), 'awaitingResponse');
        $status = $this->statusRepository->fromId(Status::awaitingResponse);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now());

        return $this->requestRepository->update($request);
    }

    /**
     * @param Request $request
     * @return Request
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToFinished(Request $request): Request
    {
        if (!($request->getStatus()->getId() == Status::approved)) {
            throw new UnauthorizedStatusChangeException();
        }

        $log = new Log(null, 'Chamado finalizado.', Carbon::now(), 'finish');
        $status = $this->statusRepository->fromId(Status::finished);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now());

        return $this->requestRepository->update($request);
    }

    /**
     * @param Request $request
     * @return Request
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToCanceled(Request $request): Request
    {
        if (
            !($request->getStatus()->getId() == Status::awaitingSupport) &&
            !($request->getStatus()->getId() == Status::inAttendance) &&
            !($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $log = new Log(null, 'Chamado cancelado.', Carbon::now(), 'cancel');
        $status = $this->statusRepository->fromId(Status::canceled);

        $request->getLogs()->add($log);
        $request->setStatus($status);
        $request->setUpdatedAt(Carbon::now());

        return $this->requestRepository->update($request);
    }

     /**
     * @param UpdateRequestCommand $command
     * @return Request|null
     * @throws RequestNotFoundException
     * @throws UnauthorizedStatusUpdateException
     */
    public function update(UpdateRequestCommand $command)
    {
        $request = $this->findById($command->getId());

        if (
            !($request->getStatus()->getId() == Status::awaitingResponse) &&
            !($request->getStatus()->getId() == Status::awaitingSupport)
        ) {
            throw new UnauthorizedStatusUpdateException();
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

        $log = new Log(null, 'Chamado alterado', Carbon::now(), $statusName);

        $request->getLogs()->add($log);
        $request->setUpdatedAt(Carbon::now());

        return $this->requestRepository->update($request);
    }

    /**
     * @param TransferCompanyCommand $command
     * @return Request
     * @throws CompanyNotFoundException
     * @throws RequestNotFoundException
     * @throws SectionNotFoundException
     * @throws UnauthorizedStatusChangeException
     * @throws UnauthorizedTransferCompanyException
     */
    public function transferCompany(TransferCompanyCommand $command){

        $request = $this->findById($command->getRequestId());
        if (
            !($request->getStatus()->getId() == Status::awaitingSupport) &&
            !($request->getStatus()->getId() == Status::inAttendance) &&
            !($request->getStatus()->getId() == Status::awaitingResponse)
        ) {
            throw new UnauthorizedStatusChangeException();
        }

        $company = $this->companyRepository->fromId($command->getCompanyId());

        if(is_null($company)){
            throw new CompanyNotFoundException();
        }

        $section = $this->sectionRepository->fromName($command->getSection());

        if(is_null($section)){
            throw new SectionNotFoundException();
        }

        if(!($company->sections()->contains($section))){
            throw new UnauthorizedTransferCompanyException();
        }

        $request->setSection($section->name());

        $request->setCompanyId($company->id());

        $request->setAssignedTo(null);


        $log = new Log(null, 'Chamado transferido', Carbon::now(), 'transfer');

        $request->getLogs()->add($log);
        $request->setUpdatedAt(Carbon::now());

        $this->requestRepository->update($request);

        $this->moveToAwaitingSupport($request);

        return $request;
    }
    
    /**
     * @param CreatePdfQuery $query
     * @return array
     * @throws StatusNotFoundException
     * @throws \Mpdf\MpdfException
     * @throws Exception
     */
    public function createPdf(CreatePdfQuery $query)
    {
        $status = null;

        if (!is_null($query->getStatusId())) {
            $status = $this->statusRepository->fromId($query->getStatusId());
            if (is_null($status)) {
                throw new StatusNotFoundException();
            }
        }

        $result = $this->requestRepository->getSearchRequests(
            $query->getTitle(),
            $query->getInitialDate(),
            $query->getFinalDate(),
            $status,
            $query->getAssignedTo(),
            $query->getRequestedBy()
        );

        if($result == []){
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
        foreach ($result as $r){
            $mpdf->WriteHTML('<tr><td>'.$r->getTitle().'</td><td>'.$r->getPriority().'</td><td>'.$r->getSection().'</td><td>'.new Carbon($r->getUpdatedAt()).'</td></tr>');
        }
        $mpdf->WriteHTML('</table>');
        $uuid = $uuid->serialize();
        $mpdf->Output($uuid.'.pdf', \Mpdf\Output\Destination::FILE);
        $url = $this->s3->sendFile('request',$uuid,$uuid.'.pdf','requestsFile.pdf','application/pdf');
        return ["url" => $url];
    }
}