<?php


namespace App\Company\Domain\Repository;

use App\Company\Domain\Entity\SLA;

interface SLARepository
{
    public function getAll();
}