<?php


namespace App\Company\Domain\Repository;


interface CompanyRepository
{
    public function fromId(int $id);

    public function getAll();

    public function getMother();
}