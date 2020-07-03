<?php


namespace App\Core\Infrastructure\Storaged\AWS;


use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class S3
{
    const bucket = "call2r";
    const version = "latest";
    const region = "sa-east-1";

    /**
     * @var S3Client
     */
    private $s3Client;

    /**
     * S3 constructor.
     */
    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => S3::version,
            'region' => S3::region,
            'credentials' => [
                'key' => $_ENV['AWS_ACCESS_KEY_ID'],
                'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
            ]
        ]);
    }

    /**
     * @param string $cpf
     * @param UploadedFile $uploadedFile
     * @return mixed|null
     */
    public function sendImage(string $cpf, UploadedFile $uploadedFile)
    {
        /*
         * Validates if it's an image
         */
        $tmp = explode("/", $uploadedFile->getMimeType());

        if ($tmp[0] != "image") {
            return null;
        }

        try {
            /*
             * Send image to amazon S3 and return a URL to this image
             */
            $fileName = $uploadedFile->getPathname();
            $url = $this->s3Client->putObject(array(
                'Bucket' => self::bucket,
                'Key' => 'user/' . $cpf . '/image',
                'SourceFile' => $fileName,
                'ACL' => "public-read",
                'ContentType' => $uploadedFile->getMimeType()
            ))->toArray()['@metadata']['effectiveUri'];
        } catch (S3Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        return $url;
    }
}