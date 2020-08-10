<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Command\MoveToAwaitingSupportCommand;
use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

/**
 * Class MoveToAwaitingSupportAction
 * @package App\Attendance\Presentation\Http\Action
 */
class MoveToAwaitingSupportAction extends AbstractAction
{
    /**
     * @var RequestService
     */
    private $service;

    /**
     * MoveToAwaitingSupportAction constructor.
     * @param RequestService $service
     */
    public function __construct(RequestService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param int $requestId
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $requestId, UserInterface $user)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $command = MoveToAwaitingSupportCommand::fromArray($data);
            $request = $this->service->findById($requestId);
            $request = $this->service->moveToAwaitingSupport($command, $request, $user);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($request, Response::HTTP_ACCEPTED);
    }
}