<?php

declare(strict_types=1);

namespace App\Notifier;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerNotifier implements NotifierInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private string          $recipient
    ) {}

    public function notify(string $message): void
    {
        $email = (new Email())
            ->to($this->recipient)
            ->text($message);

        $this->mailer->send($email);
    }
}