<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Service\CompanyService;
use App\Core\Presentation\Http\AbstractAction;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class FindMotherCompanyAction
 * @package App\Company\Presentation\Http\Action
 */
class FindMotherCompanyAction extends AbstractAction
{
    /**
     * @var CompanyService
     */
    private $service;

    /**
     * FindMotherCompanyAction constructor.
     * @param CompanyService $service
     */
    public function __construct(
        CompanyService $service
    )
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function __invoke()
    {
        try {
            $company = $this->service->getMother();
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($company, Response::HTTP_OK);
    }
}