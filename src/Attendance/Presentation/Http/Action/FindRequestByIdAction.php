<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Query\FindRequestByIdQuery;
use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FindRequestByIdAction extends AbstractAction
{
    /**
     * @var RequestService
     */
    private $service;

    /**
     * FindRequestByIdAction constructor.
     * @param RequestService $service
     */
    public function __construct(RequestService $service)
    {
        $this->service = $service;
    }


    public function __invoke(Request $request, int $id)
    {
        try {
            $data = ['id' => $id];
            $query = FindRequestByIdQuery::fromArray($data);
            $request = $this->service->fromId($query);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($request, Response::HTTP_OK);
    }
}