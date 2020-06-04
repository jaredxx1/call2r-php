<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Command\CreateCompanyCommand;
use App\Company\Application\Service\CompanyService;
use ErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateCompanyAction
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

    public function __invoke(Request $request)
    {

        try {
            $data = json_decode($request->getContent(), true);

            $command = CreateCompanyCommand::fromArray($data);

            $company = $this->service->create($command);


        } catch (ErrorException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse($company, 200);
    }
}