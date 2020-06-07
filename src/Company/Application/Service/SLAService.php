<?php


namespace App\Company\Application\Service;


use App\Company\Application\Command\UpdateSLACommand;
use App\Company\Application\Exception\SLANotFound;
use App\Company\Domain\Repository\SLARepository;

class SLAService
{

    /**
     * @var SLARepository
     */
    private $SLARepository;

    /**
     * SLAService constructor.
     * @param SLARepository $SLARepository
     */
    public function __construct(SLARepository $SLARepository)
    {
        $this->SLARepository = $SLARepository;
    }

    /**
     * @return mixed
     */
    public function getAll(){
        return $this->SLARepository->getAll();
    }

    /**
     * @param UpdateSLACommand $command
     * @return \App\Company\Domain\Entity\SLA
     * @throws SLANotFound
     */
    public function update(UpdateSLACommand $command){
        $id = $command->id();
        $sla = $this->SLARepository->fromId($id);

        if(empty($sla)){
            throw new SLANotFound();
        }

        $sla->setP1($command->p1());
        $sla->setP2($command->p2());
        $sla->setP3($command->p3());
        $sla->setP4($command->p4());
        $sla->setP5($command->p5());

        $this->SLARepository->update($sla);

        return $sla;
    }

}