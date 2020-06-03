<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Container\Application\Utils\Command;


interface CommandInterface
{

    /**
     * @param array $data
     */
    public static function fromArray($data);

    /**
     * @return array
     */
    public function toArray(): array;
}