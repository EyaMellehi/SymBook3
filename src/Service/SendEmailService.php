<?php
namespace App\Service;

use PhpParser\Node\Expr\Cast\String_;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailService{
    public function __construct(private MailerInterface $mailer)
    {}
    public function send(
        String $from,
        String $to,
        String $subject,
        String $htmlTemplate,
        array $context
    ):void{
        $email=(new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate("registration/$htmlTemplate.html.twig")
        ->context($context);
        $this->mailer->send($email);
    }
}