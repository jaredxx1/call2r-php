<?php


namespace App\User\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Application\Exception\InvalidFileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Webmozart\Assert\Assert;

/**
 * Class UpdateUserImageCommand
 * @package App\User\Application\Command
 */
class UpdateUserImageCommand implements CommandInterface
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var UploadedFile
     */
    private $uploadFile;

    /**
     * UpdateUserImageCommand constructor.
     * @param int $id
     * @param UploadedFile $uploadFile
     */
    public function __construct(int $id, UploadedFile $uploadFile)
    {
        $this->id = $id;
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
            $data['id'],
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return UploadedFile
     */
    public function getUploadFile(): UploadedFile
    {
        return $this->uploadFile;
    }
}