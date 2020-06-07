<?php


namespace App\Company\Application\Service;


use App\Company\Application\Command\UpdateSlaCommand;
use App\Company\Application\Exception\SlaNotFoundException;
use App\Company\Domain\Entity\SLA;
use App\Company\Domain\Repository\SlaRepository;

class SlaService
{

    /**
     * @var SlaRepository
     */
    private $SlaRepository;

    /**
     * SlaService constructor.
     * @param SlaRepository $SLARepository
     */
    public function __construct(SlaRepository $SLARepository)
    {
        $this->SlaRepository = $SLARepository;
    }

    /**
     * @return mixed
     */
    public function getAll(){
        return $this->SlaRepository->getAll();
    }

    /**
     * @param UpdateSlaCommand $command
     * @return SLA
     * @throws SlaNotFoundException
     */
    public function update(UpdateSlaCommand $command): ?SLA
    {
        $id = $command->id();
        $sla = $this->SlaRepository->fromId($id);

        if(empty($sla)){
            throw new SlaNotFoundException();
        }

        $sla->setP1($command->p1());
        $sla->setP2($command->p2());
        $sla->setP3($command->p3());
        $sla->setP4($command->p4());
        $sla->setP5($command->p5());

        $this->SlaRepository->update($sla);

        return $sla;
    }

}