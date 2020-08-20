<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Query\ExportRequestsToPdfQuery;
use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

class ExportRequestsToPdfAction extends AbstractAction
{
    /**
     * @var RequestService
     */
    private $service;

    /**
     * ExportRequestsToPdfAction constructor.
     * @param RequestService $service
     */
    public function __construct(RequestService $service)
    {
        $this->service = $service;
    }


    public function __invoke(Request $request, UserInterface $user)
    {
        try {
            $params = $request->query->all();
            $query = ExportRequestsToPdfQuery::fromArray($params);
            $url = $this->service->ExportsRequestsToPdf($query, $user);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($url, Response::HTTP_OK);
    }
}