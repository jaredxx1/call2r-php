<?php


namespace App\Company\Domain\Repository;

use App\Company\Domain\Entity\SLA;

interface SlaRepository
{
    public function fromId(int $id): ?SLA;

    public function update(SLA $sla): ?SLA;
}