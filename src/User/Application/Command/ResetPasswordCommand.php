<?php


namespace App\User\Application\Command;


use App\Core\Infrastructure\Container\Application\Exception\InvalidDateFormatException;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Carbon\Carbon;
use Webmozart\Assert\Assert;

/**
 * Class ResetPasswordCommand
 * @package App\User\Application\Command
 */
class ResetPasswordCommand implements CommandInterface
{

    /**
     * @var string
     */
    private $cpf;

    /**
     * @var
     */
    private $birthdate;

    /**
     * ResetPasswordCommand constructor.
     * @param string $cpf
     * @param $birthdate
     */
    public function __construct(string $cpf, $birthdate)
    {
        $this->cpf = $cpf;
        $this->birthdate = $birthdate;
    }

    /**
     * @param array $data
     * @return ResetPasswordCommand
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'cpf', 'Field cpf is required');
        Assert::keyExists($data, 'birthdate', 'Field birthdate is required');

        try {
            $birthdate = Carbon::createFromFormat('Y-m-d', $data['birthdate']);
            $data['birthdate'] = $birthdate;
        } catch (Exception $e) {
            throw new InvalidDateFormatException();
        }

        Assert::string($data['cpf'], 'Field cpf must be a string');

        return new self(
            $data['cpf'],
            $data['birthdate']
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getCpf(): string
    {
        return $this->cpf;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

}