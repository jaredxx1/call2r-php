<?php


namespace App\Wiki\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Wiki\Application\Service\WikiCategoryService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class FindAllWikiCategoryAction extends AbstractAction
{
    /**
     * @var WikiCategoryService
     */
    private $service;

    /**
     * FindAllWikiCategoryAction constructor.
     * @param WikiCategoryService $service
     */
    public function __construct(
        WikiCategoryService $service
    )
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function __invoke()
    {
        try {
            $wikiCategories = $this->service->getAll();
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($wikiCategories, 200);
    }
}