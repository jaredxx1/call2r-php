<?php


namespace App\Wiki\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Wiki\Application\Command\CreateWikiCategoryCommand;
use App\Wiki\Application\Service\WikiCategoryService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class CreateWikiCategoryAction extends AbstractAction
{
    /**
     * @var WikiCategoryService
     */
    private $service;

    /**
     * CreateWikiCategoryAction constructor.
     * @param WikiCategoryService $service
     */
    public function __construct(WikiCategoryService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $command = CreateWikiCategoryCommand::fromArray($data);
            $wikiCategory = $this->service->create($command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($wikiCategory, 200);
    }


}