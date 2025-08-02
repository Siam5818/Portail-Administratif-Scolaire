<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Bulletin;

class BulletinTuteurNotification extends Notification
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
            ->subject('Bulletin de ' . $eleve->prenom . ' ' . $eleve->nom . ' disponible')
            ->greeting('Bonjour ' . $notifiable->prenom)
            ->line('Le bulletin de notes de ' . $eleve->prenom . ' pour la période ' . $this->bulletin->periode . ' ' . $this->bulletin->annee . ' est maintenant disponible.')
            ->line('Moyenne générale : ' . $moyenne . '/20')
            ->line('Mention obtenue : ' . $mention)
            ->action('Consulter le bulletin', url($frontendUrl . '/espace-famille'))
            ->line('Nous vous invitons à consulter ce bulletin et à discuter des résultats avec ' . $eleve->prenom . '.')
            ->line('N\'hésitez pas à contacter l\'établissement si vous avez des questions.');
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
