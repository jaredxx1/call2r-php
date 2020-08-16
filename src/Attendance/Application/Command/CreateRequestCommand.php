<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Application\Exception\CreateRequestException;
use App\Company\Application\Service\CompanyService;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Application\Exception\InvalidUserPrivileges;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

class CreateRequestCommand implements CommandInterface
{
    /**
     * @var int
     */
    private $companyId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var string
     */
    private $section;

    /**
     * @var User
     */
    private $user;

    /**
     * CreateRequestCommand constructor.
     * @param int $companyId
     * @param string $title
     * @param string $description
     * @param int $priority
     * @param string $section
     * @param User $user
     */
    public function __construct(int $companyId, string $title, string $description, int $priority, string $section, User $user)
    {
        $this->companyId = $companyId;
        $this->title = $title;
        $this->description = $description;
        $this->priority = $priority;
        $this->section = $section;
        $this->user = $user;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'companyId', 'Field companyId is required');
        Assert::keyExists($data, 'title', 'Field title is required');
        Assert::keyExists($data, 'description', 'Field description is required');
        Assert::keyExists($data, 'priority', 'Field priority is required');
        Assert::keyExists($data, 'section', 'Field section is required');

        Assert::integer($data['companyId'], ' Field company id is not an integer');
        Assert::string($data['title'], ' Field title is not a string');
        Assert::string($data['description'], ' Field description is not a string');
        Assert::integer($data['priority'], ' Field priority is not an integer');
        Assert::string($data['section'], ' Field section is not a string');

        Assert::stringNotEmpty($data['title'], 'Field title is empty');
        Assert::stringNotEmpty($data['description'], 'Field description is empty');
        Assert::stringNotEmpty($data['section'], 'Field section is empty');

        if(!self::validateCreateRequest($data['user'])){
            throw new CreateRequestException();
        }

        return new self(
            $data['companyId'],
            $data['title'],
            $data['description'],
            $data['priority'],
            $data['section'],
            $data['user']
        );
    }

    /**
     * @param User $user
     * @return bool
     */
    private static function validateCreateRequest(User $user)
    {
        if($user->getRole() == User::client){
            return true;
        }

        if($user->getRole() == User::manager){
            return $user->getCompanyId() == CompanyService::motherId;
        }

        return false;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->section;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}