<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Service\CompanyService;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListCompaniesAction
{

    /**
     * @var CompanyService
     */
    private $service;

    public function __construct(
        CompanyService $service
    )
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        $companies = $this->service->getAll();

        return new JsonResponse($companies, 200);
    }
}