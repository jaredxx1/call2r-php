<?php


namespace App\Wiki\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use App\Wiki\Application\Command\DeleteCategoryCommand;
use App\Wiki\Application\Command\UpdateCategoryCommand;
use App\Wiki\Application\Service\CategoryService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class DeleteCategoryAction extends AbstractAction
{
    /**
     * @var CategoryService
     */
    private $service;

    /**
     * UpdateCategoryAction constructor.
     * @param CategoryService $service
     */
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param int $idCompany
     * @param int $idCategory
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $idCompany, int $idCategory)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $data['urlCompany'] = $idCompany;
            $data['urlCategory'] = $idCategory;
            $command = DeleteCategoryCommand::fromArray($data);
            $this->service->delete($command);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
        }
        return new JsonResponse(null, 204);
    }
}