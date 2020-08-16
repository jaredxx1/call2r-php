<?php


namespace App\User\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Application\Exception\InvalidFileException;
use App\User\Domain\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Webmozart\Assert\Assert;

/**
 * Class UpdateUserImageCommand
 * @package App\User\Application\Command
 */
class UpdateUserImageCommand implements CommandInterface
{

    /**
     * @var User
     */
    private $user;

    /**
     * @var UploadedFile
     */
    private $uploadFile;

    /**
     * UpdateUserImageCommand constructor.
     * @param User $user
     * @param UploadedFile $uploadFile
     */
    public function __construct(User $user, UploadedFile $uploadFile)
    {
        $this->user = $user;
        $this->uploadFile = $uploadFile;
    }

    /**
     * @param array $data
     * @return UpdateUserImageCommand
     * @throws InvalidFileException
     */
    public static function fromArray($data)
    {

        Assert::notNull($data['uploadFile'], 'File is null');

        $image = $data['uploadFile'];

        if (!preg_match('/image\//', $image->getMimeType())) {
            throw new InvalidFileException();
        }

        return new self(
            $data['user'],
            $data['uploadFile']
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return UploadedFile
     */
    public function getUploadFile(): UploadedFile
    {
        return $this->uploadFile;
    }

}