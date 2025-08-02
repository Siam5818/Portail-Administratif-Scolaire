<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TuteurWelcomeNotification extends Notification
{
    use Queueable;
    public string $defaultPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $defaultPassword)
    {
        $this->defaultPassword = $defaultPassword;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = config('notifications.welcome.frontend_url');
        
        return (new MailMessage)
            ->subject('Bienvenue sur le portail scolaire')
            ->greeting('Bonjour ' . $notifiable->prenom . ' 👋')
            ->line('Votre compte de tuteur a été créé avec succès.')
            ->line('Email : ' . $notifiable->email)
            ->line('Mot de passe par défaut : ' . $this->defaultPassword)
            ->line('Vous pourrez le modifier une fois connecté.')
            ->action('Se connecter au portail', url($frontendUrl . '/login'))
            ->line('Merci et à très bientôt !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
