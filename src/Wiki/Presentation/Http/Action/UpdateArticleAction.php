<?php


namespace App\Wiki\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Wiki\Application\Command\CreateArticleCommand;
use App\Wiki\Application\Command\UpdateArticleCommand;
use App\Wiki\Application\Service\ArticleService;
use Exception;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class UpdateArticleAction extends AbstractAction
{
    /**
     * @var ArticleService
     */
    private $service;

    /**
     * UpdateArticleAction constructor.
     * @param ArticleService $service
     */
    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, int $id)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $data['url'] = $id;
            $command = UpdateArticleCommand::fromArray($data);
            $article = $this->service->update($command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }
        return new JsonResponse($article, 201);
    }

}