<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Eleve;
use App\Models\Tuteur;
use App\Models\Classe;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class WelcomeNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_notification_eleve_bienvenue_envoyee()
    {
        $classe = Classe::factory()->create();
        
        $eleveUser = User::factory()->create([
            'role' => 'eleve',
            'email' => 'eleve@test.com',
            'prenom' => 'Jean'
        ]);
        
        $eleve = Eleve::factory()->create([
            'user_id' => $eleveUser->id,
            'classe_id' => $classe->id
        ]);

        $notificationService = app(NotificationService::class);
        $notificationService->envoyerNotificationEleve($eleve, 'password123');

        Notification::assertSentTo($eleveUser, \App\Notifications\EleveWelcomeNotification::class);
    }

    public function test_notification_tuteur_bienvenue_envoyee()
    {
        $tuteurUser = User::factory()->create([
            'role' => 'tuteur',
            'email' => 'tuteur@test.com',
            'prenom' => 'Marie'
        ]);

        $notificationService = app(NotificationService::class);
        $notificationService->envoyerNotificationTuteur($tuteurUser, 'password123');

        Notification::assertSentTo($tuteurUser, \App\Notifications\TuteurWelcomeNotification::class);
    }

    public function test_notifications_creation_eleve_avec_tuteur()
    {
        $classe = Classe::factory()->create();
        
        // Créer le tuteur
        $tuteurUser = User::factory()->create([
            'role' => 'tuteur',
            'email' => 'tuteur@test.com'
        ]);
        $tuteur = Tuteur::factory()->create([
            'user_id' => $tuteurUser->id
        ]);
        
        // Créer l'élève
        $eleveUser = User::factory()->create([
            'role' => 'eleve',
            'email' => 'eleve@test.com'
        ]);
        $eleve = Eleve::factory()->create([
            'user_id' => $eleveUser->id,
            'classe_id' => $classe->id,
            'tuteur_id' => $tuteur->id
        ]);

        $notificationService = app(NotificationService::class);
        $notificationService->envoyerNotificationsCreationEleve(
            $eleve, 
            'passwordEleve123', 
            'passwordTuteur123'
        );

        // Vérifier que les deux notifications ont été envoyées
        Notification::assertSentTo($eleveUser, \App\Notifications\EleveWelcomeNotification::class);
        Notification::assertSentTo($tuteurUser, \App\Notifications\TuteurWelcomeNotification::class);
    }

    public function test_notification_eleve_contient_mot_de_passe()
    {
        $classe = Classe::factory()->create();
        
        $eleveUser = User::factory()->create([
            'role' => 'eleve',
            'email' => 'eleve@test.com',
            'prenom' => 'Jean'
        ]);
        
        $eleve = Eleve::factory()->create([
            'user_id' => $eleveUser->id,
            'classe_id' => $classe->id
        ]);

        $notificationService = app(NotificationService::class);
        $notificationService->envoyerNotificationEleve($eleve, 'password123');

        Notification::assertSentTo($eleveUser, function ($notification) {
            $mailMessage = $notification->toMail($notification->notifiable);
            
            return $mailMessage->subject === 'Bienvenue sur le portail scolaire 🎓' &&
                   str_contains($mailMessage->introLines[2], 'password123');
        });
    }
} 