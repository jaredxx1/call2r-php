<?php


namespace App\Company\Application\Service;


use App\Company\Application\Command\CreateCompanyCommand;
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

    public function create(CreateCompanyCommand $command)
    {
        $company = new Company(
            $command->name(),
            $command->cnpj(),
            $command->description(),
            $command->isMother(),
            $command->isActive()
        );

        $this->companyRepository->create($company);
        return $command;
    }

    public function fromId(int $id)
    {
        $data = $this->companyRepository->fromId($id);

        if (empty($data)) {
            throw new Exception('Company not found');
        }

        return $data;
    }
}