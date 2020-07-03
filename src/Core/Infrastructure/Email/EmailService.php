<?php


namespace App\Core\Infrastructure\Email;

use PHPMailer\PHPMailer\PHPMailer;

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
     * @param string $name
     * @param string $password
     * @return bool|string
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendEmail(string $email, string $name, string $password)
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
        $this->phpMailer->addAddress($email, $name);

        /*
         * Title of email
         */
        $this->phpMailer->Subject = 'Your new password';

        /*
         * Template of email
         */
        $this->phpMailer->msgHTML('<H1> ' . $name . ', your new password has arrived ' . $password . '</H1>');

        /*
         * send email
         */
        $response = $this->phpMailer->send();

        if (!$response) {
            return $this->phpMailer->ErrorInfo;
        }
        return $response;
    }
}