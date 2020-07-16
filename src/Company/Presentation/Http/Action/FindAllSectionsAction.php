<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Service\SectionService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class FindAllSectionsAction
 * @package App\Company\Presentation\Http\Action
 */
class FindAllSectionsAction extends AbstractAction
{
    /**
     * @var SectionService
     */
    private $service;


    /**
     * FindAllSectionsAction constructor.
     * @param SectionService $service
     */
    public function __construct(
        SectionService $service
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
            $sections = $this->service->getAll();
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($sections, Response::HTTP_OK);
    }
}