<?php


namespace App\Company\Application\Query;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;
use Webmozart\Assert\Assert;

class FindCompanyByIdQuery implements QueryInterface
{

    public static function fromId($data)
    {
        Assert::integer($data, "Id must be a integer");

        return $data;
    }

    public static function convertId($data){
        Assert::string($data, 'error');

        return intval($data);
    }
}