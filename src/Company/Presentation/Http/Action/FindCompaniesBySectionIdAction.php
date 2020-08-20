<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Query\FindCompaniesBySectionIdQuery;
use App\Company\Application\Service\CompanyService;
use App\Core\Presentation\Http\AbstractAction;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FindCompaniesBySectionIdAction extends AbstractAction
{
    /**
     * @var CompanyService
     */
    private $service;

    /**
     * FindCompanyByIdAction constructor.
     * @param CompanyService $service
     */
    public function __construct(
        CompanyService $service
    )
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param int $sectionId
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $sectionId)
    {
        try {
            $data = ['sectionId' => $sectionId];
            $query = FindCompaniesBySectionIdQuery::fromArray($data);
            $company = $this->service->getCompaniesBySection($query);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($company, Response::HTTP_OK);
    }
}