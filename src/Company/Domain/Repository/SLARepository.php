<?php


namespace App\Company\Domain\Repository;

use App\Company\Domain\Entity\SLA;

interface SLARepository
{
    public function fromId(int $id): ?SLA;

    public function getAll();

    public function update(SLA $sla): ?SLA;
}