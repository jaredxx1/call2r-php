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
        Assert::notNull($data['mother'], 'Mother is required');
        Assert::notNull($data['active'], 'Active is required');

        Assert::string($data['name'], 'Name is not a string');
        Assert::string($data['description'], 'Description is not a string');
        Assert::string($data['cnpj'], 'CNPJ is not a string');
        Assert::boolean($data['mother'], 'Mother is not a boolean');
        Assert::boolean($data['active'], 'Active is not a boolean');

        Assert::stringNotEmpty($data['name'], 'Name is empty');
        Assert::stringNotEmpty($data['description'], 'Description is empty');
        Assert::stringNotEmpty($data['cnpj'], 'CNPJ is empty');

        Assert::length($data['cnpj'],14, "CNPJ dont have 14 digits");

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