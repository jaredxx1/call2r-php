<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Command\UpdateSectionCommand;
use App\Company\Application\Service\SectionService;
use App\Core\Presentation\Http\AbstractAction;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class UpdateSectionAction
 * @package App\Company\Presentation\Http\Action
 */
class UpdateSectionAction extends AbstractAction
{
    /**
     * @var SectionService
     */
    private $service;


    /**
     * UpdateSectionAction constructor.
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
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $data['url'] = $id;
            $command = UpdateSectionCommand::fromArray($data);
            $section = $this->service->update($command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($section, 200);
    }
}