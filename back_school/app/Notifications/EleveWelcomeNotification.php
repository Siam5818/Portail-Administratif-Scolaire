<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EleveWelcomeNotification extends Notification
{
    use Queueable;
    public string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $password) {
        $this->password = $password;
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
        return (new MailMessage)
            ->subject('Bienvenue sur le portail scolaire 🎓')
            ->greeting('Salut ' . $notifiable->prenom)
            ->line('Ton compte élève a été créé avec succès.')
            ->line('Email : ' . $notifiable->email)
            ->line('Mot de passe par défaut : ' . $this->password)
            ->line('Vous pourrez le modifier une fois connecté.')
            ->action('Connecte-toi pour découvrir +.', url('http://127.0.0.1:4200/login'))
            ->line('À très bientôt sur le portail !');
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
