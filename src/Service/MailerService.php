<?php

namespace App\Service;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    public function __construct(private MailerInterface $mailer)
    {
            $this->mailer = $mailer;
    }

    public function sendEmail(
        $to ,
        $template,
        $subject,
        $context
    ): void
    {
        $email = (new TemplatedEmail())
            ->from('medcare.nonreply@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->htmlTemplate("Front-Office/Template-Email/$template.html.twig")
            ->context($context);
             $this->mailer->send($email);

        // ...
    }

}