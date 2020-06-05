<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Query\FindCompanyByIdQuery;
use App\Company\Application\Service\CompanyService;
use App\Core\Presentation\Http\AbstractAction;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class FindCompanyByIdAction extends AbstractAction
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

    public function __invoke(Request $request, int $id)
    {
        try {
            $data = ['id' => $id];
            $query = FindCompanyByIdQuery::fromArray($data);
            $company = $this->service->fromId($query);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }

        return new JsonResponse($company, 200);
    }
}