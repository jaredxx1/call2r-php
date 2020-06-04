<?php


namespace App\Company\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class CreateCompanyCommand implements CommandInterface
{

    public static function fromArray($data)
    {
        Assert::notNull($data['name'], 'Name is required');
        Assert::notNull($data['description'], 'Description is required');
        Assert::notNull($data['cnpj'], 'CNPJ is required');
        Assert::boolean($data['mother'], 'Mother is required');
        Assert::boolean($data['active'], 'Active is required');

        return [
            'name' => $data['name'],
            'description' => $data['description'],
            'cnpj' => $data['cnpj'],
            'mother' => $data['mother'],
            'active' => $data['active']
        ];
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}