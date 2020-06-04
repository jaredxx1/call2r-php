<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Query\FindCompanyByIdQuery;
use App\Company\Application\Service\CompanyService;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;
use Symfony\Component\HttpFoundation\JsonResponse;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class CompanyByIdAction
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

    public function __invoke($id)
    {
        try {
            $data = FindCompanyByIdQuery::convertId($id);
            $data = FindCompanyByIdQuery::fromId($data);
            $company = $this->service->fromId($data);
        }catch (Exception $exception){
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        } catch (InvalidArgumentException $exception){
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse($company, 200);
    }
}