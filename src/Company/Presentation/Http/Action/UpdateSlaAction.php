<?php


namespace App\Company\Presentation\Http\Action;


use App\Company\Application\Command\UpdateSlaCommand;
use App\Company\Application\Service\SlaService;
use App\Core\Presentation\Http\AbstractAction;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class UpdateSlaAction extends AbstractAction
{
    /**
     * @var SlaService
     */
    private $service;

    /**
     * FindAllSlaAction constructor.
     * @param SlaService $service
     */
    public function __construct(SlaService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request)
    {
       try{
           $data = json_decode($request->getContent(),true);
           $command = UpdateSlaCommand::fromArray($data);
           $sla = $this->service->update($command);
       } catch (Exception $exception) {
           return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
       } catch (Throwable $exception) {
           return $this->errorResponse($exception->getMessage(), $exception->getCode() ? $exception->getCode() : 400);
       }

       return new JsonResponse($sla, 200);
    }
}