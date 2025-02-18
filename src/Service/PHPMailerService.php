<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerService
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
    }

    public function sendEmail(string $toEmail, string $subject, string $messageContent): void
    {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = 'smtp.gmail.com';
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = 'demoineretnathan@gmail.com';
            $this->mailer->Password = 'uxik gxsh qylv utmf';
            $this->mailer->SMTPSecure = 'ssl';
            $this->mailer->Port = 465;
            $this->mailer->SMTPDebug = 2;
            $this->mailer->Debugoutput = 'html';

            $this->mailer->setFrom('nathandmt@ik.me', 'GalleryProject');
            $this->mailer->addAddress($toEmail);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $messageContent;

            $this->mailer->send();
        } catch (Exception $e) {
            throw new \RuntimeException("Erreur lors de l'envoi de l'email : " . $this->mailer->ErrorInfo);
        }
    }
}
