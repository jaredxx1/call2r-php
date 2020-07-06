<?php


namespace App\Attendance\Presentation\Http\Action;


use App\Core\Presentation\Http\AbstractAction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PdfGeneratorAction extends AbstractAction
{

    public function __invoke()
    {
        return new JsonResponse([],Response::HTTP_OK);
    }
}