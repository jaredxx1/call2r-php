<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Attendance\Application\Command\UpdateRequestCommand;
use App\Attendance\Application\Query\CreatePdfQuery;
use App\Attendance\Application\Service\RequestService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CreatePdfAction extends AbstractAction
{
    /**
     * @var RequestService
     */
    private $service;

    /**
     * CreatePdfAction constructor.
     * @param RequestService $service
     */
    public function __construct(RequestService $service)
    {
        $this->service = $service;
    }


    public function __invoke(Request $request)
    {
        try {
            if(!is_null($request->query->get('title'))){
                $data['title'] = $request->query->get('title');
            }
            if(!is_null($request->query->get('initialDate'))){
                $data['initialDate'] = $request->query->get('initialDate');
            }
            if(!is_null($request->query->get('finalDate'))){
                $data['finalDate'] = $request->query->get('finalDate');
            }
            if(!is_null($request->query->get('status'))){
                $data['status'] = $request->query->get('status');
            }
            if(!is_null($request->query->get('assignedTo'))){
                $data['assignedTo'] = $request->query->get('assignedTo');
            }
            if(!is_null($request->query->get('requestedBy'))){
                $data['requestedBy'] = $request->query->get('requestedBy');
            }
            $query = CreatePdfQuery::fromArray($data);
            $url = $this->service->createPdf($query);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($url, Response::HTTP_OK);
    }
}