<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Command\CreateCompanyCommand;
use App\Company\Application\Service\CompanyService;
use App\Core\Presentation\Http\AbstractAction;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class CreateCompanyAction
 * @package App\Company\Presentation\Http\Action
 */
class CreateCompanyAction extends AbstractAction
{

    /**
     * @var CompanyService
     */
    private $service;

    /**
     * CreateCompanyAction constructor.
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
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {

        try {
            $data = json_decode($request->getContent(), true);
            $command = CreateCompanyCommand::fromArray($data);
            $company = $this->service->create($command);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($company, 201);
    }
}