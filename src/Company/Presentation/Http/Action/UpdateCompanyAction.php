<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Command\UpdateCompanyCommand;
use App\Company\Application\Service\CompanyService;
use App\Core\Presentation\Http\AbstractAction;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UpdateCompanyAction
 * @package App\Company\Presentation\Http\Action
 */
class UpdateCompanyAction extends AbstractAction
{

    /**
     * @var CompanyService
     */
    private $service;

    /**
     * UpdateCompanyAction constructor.
     * @param CompanyService $service
     */
    public function __construct(CompanyService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $data['url'] = $id;
            $command = UpdateCompanyCommand::fromArray($data);
            $company = $this->service->update($command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($company, Response::HTTP_OK);
    }
}