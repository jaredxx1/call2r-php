<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Command\CreateRequestCommand;
use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class CreateRequestAction
 * @package App\Attendance\Presentation\Http\Action
 */
class CreateRequestAction extends AbstractAction
{
    /**
     * @var RequestService
     */
    private $service;


    /**
     * CreateRequestAction constructor.
     * @param RequestService $service
     */
    public function __construct(RequestService $service)
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
            $command = CreateRequestCommand::fromArray($data);
            $request = $this->service->create($command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }
        return new JsonResponse($request, 201);
    }
}