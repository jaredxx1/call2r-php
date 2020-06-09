<?php


namespace App\Company\Domain\Repository;


use App\Company\Domain\Entity\Section;

interface SectionRepository
{

    public function getAll();

    public function fromId(int $id): ?Section;

    public function fromName(string $name): ?Section;

    public function update(Section $section): ?Section;
}