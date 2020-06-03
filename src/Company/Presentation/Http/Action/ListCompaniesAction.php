<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Domain\Repository\CompanyRepository;
use App\Company\Infrastructure\Persistence\Doctrine\Repository\DoctrineCompanyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListCompaniesAction
{

    public function __invoke()
    {
        $companyRepository = new DoctrineCompanyRepository();
        $companies = $companyRepository->getAll();
        dd($companies);
        return new JsonResponse($companies, 200);
    }
}