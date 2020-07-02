<?php


namespace App\Attendance\Application\Service;


use App\Attendance\Application\Command\CreateLogCommand;
use App\Attendance\Application\Command\CreateRequestCommand;
use App\Attendance\Application\Exception\StatusNotFoundException;
use App\Attendance\Domain\Entity\Request;
use App\Attendance\Domain\Entity\Status;
use App\Attendance\Domain\Repository\RequestRepository;
use App\Attendance\Domain\Repository\StatusRepository;
use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Application\Exception\SectionNotFoundException;
use App\Company\Domain\Repository\CompanyRepository;
use App\Company\Domain\Repository\SectionRepository;
use App\Security\Application\Exception\UserNotFoundException;
use App\Security\Domain\Repository\UserRepository;
use DateTime;
use Exception;

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
    public function create(CreateRequestCommand $command)
    {
        $status = $this->statusRepository->fromId(Status::awaitingSupport);
        $company = $this->companyRepository->fromId($command->getCompanyId());

        if(is_null($company)){
            throw new CompanyNotFoundException();
        }

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
            null,
            null,
            null
        );

        LogService::registerEvent('', 'init');

        return $this->requestRepository->create($request);
    }

}