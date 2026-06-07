<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    private $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Redefinicao de senha - EpiMonitor')
            ->greeting('Olá ' . $notifiable->name)
            ->line('Recebemos uma solicitacao para redefinir a senha da sua conta no EpiMonitor.')
            ->action('Redefinir senha', $url)
            ->line('Este link expira em 60 minutos.');
    }
}
