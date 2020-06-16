<?php


namespace App\Wiki\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Wiki\Application\Query\FindAllWikiFromCompanyQuery;
use App\Wiki\Application\Service\ArticleService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class FindAllArticleFromCompanyAction extends AbstractAction
{
    /**
     * @var ArticleService
     */
    private $service;

    /**
     * FindAllWikiArticleAction constructor.
     * @param ArticleService $service
     */
    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, int $id)
    {
        try {
            $data = ['id' => $id];
            $query = FindAllWikiFromCompanyQuery::fromArray($data);
            $wikiArticles = $this->service->fromCompany($query);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }

        return new JsonResponse($wikiArticles, 200);
    }
}