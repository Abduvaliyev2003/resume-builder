<?php

namespace App\Domains\User\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected string $code)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Verification Code')
            ->greeting('Hello!')
            ->line('Your email verification code is:')
            ->line(new \Illuminate\Support\HtmlString('<div style="font-size: 24px; font-weight: bold; letter-spacing: 5px; text-align: center; margin: 20px 0; color: #2563eb;">' . $this->code . '</div>'))
            ->line('This code is valid for 10 minutes.')
            ->line('If you did not request this, no further action is required.');
    }
}
