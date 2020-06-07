<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Service\SlaService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class FindAllSlaAction extends AbstractAction
{
    /**
     * @var SlaService
     */
    private $service;

    /**
     * FindAllSlaAction constructor.
     * @param SlaService $service
     */
    public function __construct(SlaService $service)
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