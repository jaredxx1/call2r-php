<?php


namespace App\Wiki\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Wiki\Application\Query\FindAllCategoriesFromCompanyQuery;
use App\Wiki\Application\Service\CategoryService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

/**
 * Class FindAllCategoriesFromCompanyAction
 * @package App\Wiki\Presentation\Http\Action
 */
class FindAllCategoriesFromCompanyAction extends AbstractAction
{
    /**
     * @var CategoryService
     */
    private $service;

    /**
     * FindAllWikiArticleAction constructor.
     * @param CategoryService $service
     */
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(int $id)
    {
        try {
            $data = ['id' => $id];
            $query = FindAllCategoriesFromCompanyQuery::fromArray($data);
            $categories = $this->service->fromCompany($query);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($categories, 200);
    }
}