<?php


namespace App\Attendance\Application\Service;


use App\Attendance\Domain\Repository\RequestRepository;

class RequestService
{
    /**
     * @var RequestRepository
     */
    private $requestRepository;

    /**
     * RequestService constructor.
     * @param RequestRepository $requestRepository
     */
    public function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    public function findAllRequests()
    {
        return $this->requestRepository->getAll();
    }

}