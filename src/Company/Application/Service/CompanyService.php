<?php


namespace App\Company\Application\Service;


use App\Company\Domain\Entity\Company;
use App\Company\Domain\Repository\CompanyRepository;
use Symfony\Component\Config\Definition\Exception\Exception;

final class CompanyService
{

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    public function __construct(
        CompanyRepository $companyRepository
    )
    {
        $this->companyRepository = $companyRepository;
    }


    public function getAll()
    {
        return $this->companyRepository->getAll();
    }

    public function create(array $command)
    {
        $company = new Company(
            $command['name'],
            $command['cnpj'],
            $command['description'],
            $command['mother'],
            $command['active']
        );

        $this->companyRepository->create($company);
        return $command;
    }

    public function fromId(int $id){
        $data = $this->companyRepository->fromId($id);

        if (empty($data)) {
            throw new Exception('Company not found');
        }

        return $data;
    }
}