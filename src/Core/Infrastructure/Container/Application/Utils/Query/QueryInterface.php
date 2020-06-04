<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Container\Application\Utils\Query;


interface QueryInterface
{
    /**
     * @param integer $data
     */
    public static function fromId($data);

    /**
     * @param String $data
     */
    public static function convertId($data);
}