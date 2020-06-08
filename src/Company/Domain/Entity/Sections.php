<?php


namespace App\Company\Domain\Entity;


use App\Core\Domain\Entity\Collection;

class Sections extends Collection
{

    protected function type(): string
    {
        return Section::class;
    }
}