<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Service\CompanyService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class ListCompaniesAction extends AbstractAction
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
        try {
            $companies = $this->service->getAll();
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($companies, 200);
    }
}