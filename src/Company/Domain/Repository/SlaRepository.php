<?php


namespace App\Company\Domain\Repository;

use App\Company\Domain\Entity\SLA;

/**
 * Interface SlaRepository
 * @package App\Company\Domain\Repository
 */
interface SlaRepository
{
    /**
     * @param int $id
     * @return SLA|null
     */
    public function fromId(int $id): ?SLA;

    /**
     * @param SLA $sla
     * @return SLA|null
     */
    public function update(SLA $sla): ?SLA;
}