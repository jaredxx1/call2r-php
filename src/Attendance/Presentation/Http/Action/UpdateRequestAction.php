<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Command\UpdateRequestCommand;
use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

class UpdateRequestAction  extends AbstractAction
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
     * @param int $id
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $id, UserInterface $user)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $data['id'] = $id;
            $command = UpdateRequestCommand::fromArray($data);
            $request = $this->service->update($command, $user);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse($request, Response::HTTP_OK);
    }
}