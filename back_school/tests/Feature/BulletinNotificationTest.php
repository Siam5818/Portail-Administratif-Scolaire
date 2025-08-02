<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Eleve;
use App\Models\Bulletin;
use App\Models\Tuteur;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Note;
use App\Services\BulletinService;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class BulletinNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_notifications_envoyees_lors_creation_bulletin()
    {
        // Créer les données de test
        $classe = Classe::factory()->create();
        $matiere = Matiere::factory()->create();
        
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
        
        // Créer des notes pour l'élève
        Note::factory()->create([
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'note' => 15,
            'periode' => '1er Trimestre'
        ]);

        // Générer le bulletin
        $bulletinService = app(BulletinService::class);
        $bulletin = $bulletinService->generateBulletin($eleve, '1er Trimestre', 2024);

        // Vérifier que les notifications ont été envoyées
        Notification::assertSentTo(
            $eleveUser,
            \App\Notifications\BulletinDisponibleNotification::class
        );

        Notification::assertSentTo(
            $tuteurUser,
            \App\Notifications\BulletinTuteurNotification::class
        );
    }

    public function test_notification_eleve_contient_bonnes_informations()
    {
        // Créer les données de test
        $classe = Classe::factory()->create();
        $matiere = Matiere::factory()->create();
        
        $eleveUser = User::factory()->create([
            'role' => 'eleve',
            'email' => 'eleve@test.com',
            'prenom' => 'Jean'
        ]);
        $eleve = Eleve::factory()->create([
            'user_id' => $eleveUser->id,
            'classe_id' => $classe->id
        ]);
        
        Note::factory()->create([
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'note' => 16,
            'periode' => '1er Trimestre'
        ]);

        $bulletinService = app(BulletinService::class);
        $bulletin = $bulletinService->generateBulletin($eleve, '1er Trimestre', 2024);

        Notification::assertSentTo($eleveUser, function ($notification) use ($bulletin) {
            $mailMessage = $notification->toMail($notification->notifiable);
            
            return $mailMessage->subject === 'Ton bulletin de notes est disponible' &&
                   str_contains($mailMessage->greeting, 'Salut Jean') &&
                   str_contains($mailMessage->introLines[0], '1er Trimestre 2024');
        });
    }

    public function test_notification_tuteur_contient_bonnes_informations()
    {
        // Créer les données de test
        $classe = Classe::factory()->create();
        $matiere = Matiere::factory()->create();
        
        $tuteurUser = User::factory()->create([
            'role' => 'tuteur',
            'email' => 'tuteur@test.com',
            'prenom' => 'Marie'
        ]);
        $tuteur = Tuteur::factory()->create([
            'user_id' => $tuteurUser->id
        ]);
        
        $eleveUser = User::factory()->create([
            'role' => 'eleve',
            'email' => 'eleve@test.com',
            'prenom' => 'Jean',
            'nom' => 'Dupont'
        ]);
        $eleve = Eleve::factory()->create([
            'user_id' => $eleveUser->id,
            'classe_id' => $classe->id,
            'tuteur_id' => $tuteur->id
        ]);
        
        Note::factory()->create([
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'note' => 14,
            'periode' => '1er Trimestre'
        ]);

        $bulletinService = app(BulletinService::class);
        $bulletin = $bulletinService->generateBulletin($eleve, '1er Trimestre', 2024);

        Notification::assertSentTo($tuteurUser, function ($notification) use ($eleve) {
            $mailMessage = $notification->toMail($notification->notifiable);
            
            return $mailMessage->subject === 'Bulletin de Jean Dupont disponible' &&
                   str_contains($mailMessage->greeting, 'Bonjour Marie') &&
                   str_contains($mailMessage->introLines[0], 'Jean');
        });
    }
} 