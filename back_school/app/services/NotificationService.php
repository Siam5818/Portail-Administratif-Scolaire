<?php

namespace App\Services;

use App\Models\User;
use App\Models\Eleve;
use App\Models\Bulletin;
use App\Notifications\EleveWelcomeNotification;
use App\Notifications\TuteurWelcomeNotification;
use App\Notifications\EnseignantWelcomeNotification;
use App\Notifications\BulletinDisponibleNotification;
use App\Notifications\BulletinTuteurNotification;

class NotificationService
{
    /**
     * Envoie la notification de bienvenue à un nouvel élève
     */
    public function envoyerNotificationEleve(Eleve $eleve, string $password): void
    {
        if ($eleve->user && $eleve->user->email) {
            $eleve->user->notify(new EleveWelcomeNotification($password));
        }
    }

    /**
     * Envoie la notification de bienvenue à un nouveau tuteur
     */
    public function envoyerNotificationTuteur(User $tuteur, string $password): void
    {
        if ($tuteur->email) {
            $tuteur->notify(new TuteurWelcomeNotification($password));
        }
    }

    /**
     * Envoie la notification de bienvenue à un nouvel enseignant
     */
    public function envoyerNotificationEnseignant(User $enseignant, string $password): void
    {
        if ($enseignant->email) {
            $enseignant->notify(new EnseignantWelcomeNotification($password));
        }
    }

    /**
     * Envoie les notifications de bulletin disponible
     */
    public function envoyerNotificationsBulletin(Bulletin $bulletin): void
    {
        $eleve = $bulletin->eleve;
        
        // Notification à l'élève
        if ($eleve->user && $eleve->user->email) {
            $eleve->user->notify(new BulletinDisponibleNotification($bulletin));
        }
        
        // Notification au tuteur
        if ($eleve->tuteur && $eleve->tuteur->user && $eleve->tuteur->user->email) {
            $eleve->tuteur->user->notify(new BulletinTuteurNotification($bulletin));
        }
    }

    /**
     * Envoie les notifications de bienvenue lors de la création d'un élève
     */
    public function envoyerNotificationsCreationEleve(Eleve $eleve, string $passwordEleve, ?string $passwordTuteur = null): void
    {
        // Notification à l'élève
        $this->envoyerNotificationEleve($eleve, $passwordEleve);
        
        // Notification au tuteur si nouveau
        if ($passwordTuteur && $eleve->tuteur && $eleve->tuteur->user) {
            $this->envoyerNotificationTuteur($eleve->tuteur->user, $passwordTuteur);
        }
    }
} 