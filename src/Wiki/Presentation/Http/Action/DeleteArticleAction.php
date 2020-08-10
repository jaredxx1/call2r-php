<?php


namespace App\Wiki\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Wiki\Application\Command\DeleteArticleCommand;
use App\Wiki\Application\Service\ArticleService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

/**
 * Class DeleteArticleAction
 * @package App\Wiki\Presentation\Http\Action
 */
class DeleteArticleAction extends AbstractAction
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

    /**
     * @param Request $request
     * @param int $idCompany
     * @param int $idArticle
     * @param UserInterface $user
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $idCompany, int $idArticle, UserInterface $user)
    {
        try {
            $data['urlCompany'] = $idCompany;
            $data['urlArticle'] = $idArticle;
            $query = DeleteArticleCommand::fromArray($data);
            $this->service->delete($query, $user);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}