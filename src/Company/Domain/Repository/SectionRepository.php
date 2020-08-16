<?php


namespace App\Company\Domain\Repository;


use App\Company\Domain\Entity\Section;

/**
 * Interface SectionRepository
 * @package App\Company\Domain\Repository
 */
interface SectionRepository
{

    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param int $id
     * @return Section|null
     */
    public function fromId(int $id): ?Section;

    /**
     * @param string $name
     * @return Section|null
     */
    public function fromName(string $name): ?Section;

    /**
     * @param Section $section
     * @return Section|null
     */
    public function update(Section $section): ?Section;
}