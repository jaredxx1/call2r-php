<?php


namespace App\Attendance\Application\Service;


use App\Attendance\Application\Command\CreateRequestCommand;
use App\Attendance\Application\Exception\RequestNotFoundException;
use App\Attendance\Application\Exception\UnauthorizedStatusChangeException;
use App\Attendance\Domain\Entity\Log;
use App\Attendance\Domain\Entity\Request;
use App\Attendance\Domain\Entity\Status;
use App\Attendance\Domain\Repository\RequestRepository;
use App\Attendance\Domain\Repository\StatusRepository;
use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Domain\Repository\CompanyRepository;
use App\Company\Domain\Repository\SectionRepository;
use App\Security\Domain\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

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
     * RequestService constructor.
     * @param RequestRepository $requestRepository
     * @param StatusRepository $statusRepository
     * @param CompanyRepository $companyRepository
     * @param UserRepository $userRepository
     * @param SectionRepository $sectionRepository
     */
    public function __construct(RequestRepository $requestRepository, StatusRepository $statusRepository, CompanyRepository $companyRepository, UserRepository $userRepository, SectionRepository $sectionRepository)
    {
        $this->requestRepository = $requestRepository;
        $this->statusRepository = $statusRepository;
        $this->companyRepository = $companyRepository;
        $this->userRepository = $userRepository;
        $this->sectionRepository = $sectionRepository;
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
     * @return Request
     * @throws UnauthorizedStatusChangeException
     */
    public function moveToAwaitingSupport(Request $request): Request
    {
        if (
            !($request->getStatus()->getId() == Status::inAttendance) ||
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

}