<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Container\Application\Utils\Command;


interface CommandInterface
{

    /**
     * @param array $data
     * @param $id
     */
    public static function fromArray($data, ?int $id);

    /**
     * @return array
     */
    public function toArray(): array;
}