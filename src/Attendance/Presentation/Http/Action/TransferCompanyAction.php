<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Command\ApproveRequestCommand;
use App\Attendance\Application\Command\TransferCompanyCommand;
use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class TransferCompanyAction
 * @package App\Attendance\Presentation\Http\Action
 */
class TransferCompanyAction  extends AbstractAction
{
    /**
     * @var RequestService
     */
    private $service;

    /**
     * ApproveRequestAction constructor.
     * @param RequestService $service
     */
    public function __construct(RequestService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param int $requestId
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $requestId)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $data['requestId'] = $requestId;
            $command = TransferCompanyCommand::fromArray($data);
            $request = $this->service->transferCompany($command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($request, Response::HTTP_ACCEPTED);
    }
}