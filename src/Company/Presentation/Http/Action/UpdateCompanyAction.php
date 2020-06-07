<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Command\UpdateCompanyCommand;
use App\Company\Application\Service\CompanyService;
use App\Core\Presentation\Http\AbstractAction;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

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
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $command = UpdateCompanyCommand::fromArray($data);
            $company = $this->service->update($command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($company, 200);
    }
}