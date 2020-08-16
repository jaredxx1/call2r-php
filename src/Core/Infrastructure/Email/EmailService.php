<?php


namespace App\Core\Infrastructure\Email;

use App\Core\Infrastructure\Container\Application\Exception\EmailSendException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class EmailService
 * @package App\Core\Infrastructure\Email
 */
class EmailService
{

    private const emailPort = 587;
    private const emailHost = "smtp.gmail.com";
    private const emailUsername = "call2r.group@gmail.com";
    /**
     * @var PHPMailer
     */
    private $phpMailer;

    /**
     * EmailService constructor.
     */
    public function __construct()
    {
        $this->phpMailer = new PHPMailer(true);
    }

    /**
     * @param string $email
     * @param string $emailName
     * @param string $subject
     * @param string $template
     * @throws EmailSendException
     * @throws Exception
     */
    public function sendEmail(string $email, string $emailName, string $subject, string $template)
    {

        /*
         * Configuration
         */
        $this->phpMailer->isSMTP();
        $this->phpMailer->Host = self::emailHost;
        $this->phpMailer->Port = self::emailPort;
        $this->phpMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->phpMailer->SMTPAuth = true;
        $this->phpMailer->Username = self::emailUsername;
        $this->phpMailer->Password = $_ENV['EMAIL_PASSWORD'];

        /*
         * Who will send the email
         */
        $this->phpMailer->setFrom(self::emailUsername, 'Call2r');

        /*
         * Who will receive the email
         */
        $this->phpMailer->addAddress($email, $emailName);

        /*
         * Title of email
         */
        $this->phpMailer->Subject = $subject;

        /*
         * Template of email
         */
        $this->phpMailer->msgHTML($template);

        /*
         * send email
         */
        $response = $this->phpMailer->send();

        if (!$response) {
            throw new EmailSendException();
        }
    }
}