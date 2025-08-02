<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Bulletin;

class BulletinDisponibleNotification extends Notification
{
    use Queueable;

    public Bulletin $bulletin;

    /**
     * Create a new notification instance.
     */
    public function __construct(Bulletin $bulletin) {
        $this->bulletin = $bulletin;
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
        $eleve = $this->bulletin->eleve;
        $moyenne = $this->bulletin->calculMoyenne();
        $mention = $this->bulletin->mention;
        $frontendUrl = config('notifications.bulletins.frontend_url');

        return (new MailMessage)
            ->subject('Ton bulletin de notes est disponible')
            ->greeting('Salut ' . $eleve->prenom)
            ->line('Ton bulletin de notes pour la période ' . $this->bulletin->periode . ' ' . $this->bulletin->annee . ' est maintenant disponible.')
            ->line('Ta moyenne générale : ' . $moyenne . '/20')
            ->line('Mention obtenue : ' . $mention)
            ->action('Consulter ton bulletin', url($frontendUrl . '/espace-famille'))
            ->line('N\'oublie pas de le partager avec tes parents !')
            ->line('Continue tes efforts pour la prochaine période !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'bulletin_id' => $this->bulletin->id,
            'periode' => $this->bulletin->periode,
            'annee' => $this->bulletin->annee,
            'eleve_nom' => $this->bulletin->eleve->nom,
            'eleve_prenom' => $this->bulletin->eleve->prenom,
        ];
    }
}
