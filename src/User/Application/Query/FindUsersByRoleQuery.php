<?php


namespace App\User\Application\Query;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;
use Webmozart\Assert\Assert;

/**
 * Class FindUsersByRoleQuery
 * @package App\User\Application\Query
 */
class FindUsersByRoleQuery implements QueryInterface
{

    /**
     * @var string
     */
    private $role;

    /**
     * FindUsersByRoleQuery constructor.
     * @param string $role
     */
    public function __construct(string $role)
    {
        $this->role = $role;
    }

    /**
     * @param array $data
     * @return FindUsersByRoleQuery
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'role', "Parameter role is required.");
        Assert::oneOf($data['role'], ['manager-client', 'manager-support', 'client', 'support'], "Role is not valid.");

        return new self(
            $data['role']
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
    public function getRole(): string
    {
        return $this->role;
    }
}