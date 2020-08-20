<?php


namespace App\Wiki\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Wiki\Application\Query\FindAllCategoriesFromCompanyQuery;
use App\Wiki\Application\Service\CategoryService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
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
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(UserInterface $user)
    {
        try {
            $data = ['idCompany' => $user->getCompanyId()];
            $query = FindAllCategoriesFromCompanyQuery::fromArray($data);
            $categories = $this->service->fromCompany($query, $user);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($categories, Response::HTTP_OK);
    }
}