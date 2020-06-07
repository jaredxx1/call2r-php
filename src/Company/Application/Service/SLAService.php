<?php


namespace App\Company\Application\Service;


use App\Company\Domain\Repository\SLARepository;

class SLAService
{

    /**
     * @var SLARepository
     */
    private $repository;

    /**
     * SLAService constructor.
     * @param SLARepository $repository
     */
    public function __construct(SLARepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function getAll(){
        return $this->repository->getAll();
    }

}