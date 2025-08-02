<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Eleve;
use App\Models\Bulletin;
use App\Models\Classe;
use App\Models\Matiere;
use App\Models\Note;
use App\Notifications\EleveWelcomeNotification;
use App\Notifications\BulletinDisponibleNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_eleve_welcome_notification_has_correct_url()
    {
        // Configurer l'URL frontend
        config(['notifications.welcome.frontend_url' => 'http://localhost:4200']);
        
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

        $notification = new EleveWelcomeNotification('password123');
        $mailMessage = $notification->toMail($eleveUser);

        // Vérifier que l'URL est correcte
        $this->assertStringContainsString('http://localhost:4200/login', $mailMessage->actionUrl);
        $this->assertEquals('Se connecter au portail', $mailMessage->actionText);
    }

    public function test_bulletin_notification_has_correct_url()
    {
        // Configurer l'URL frontend
        config(['notifications.bulletins.frontend_url' => 'http://localhost:4200']);
        
        $classe = Classe::factory()->create();
        $matiere = Matiere::factory()->create();
        
        $eleveUser = User::factory()->create([
            'role' => 'eleve',
            'email' => 'eleve@test.com'
        ]);
        
        $eleve = Eleve::factory()->create([
            'user_id' => $eleveUser->id,
            'classe_id' => $classe->id
        ]);
        
        Note::factory()->create([
            'eleve_id' => $eleve->id,
            'matiere_id' => $matiere->id,
            'note' => 15,
            'periode' => '1er Trimestre'
        ]);

        $bulletin = Bulletin::create([
            'eleve_id' => $eleve->id,
            'periode' => '1er Trimestre',
            'annee' => 2024,
            'pdf_name' => 'test.pdf'
        ]);

        $notification = new BulletinDisponibleNotification($bulletin);
        $mailMessage = $notification->toMail($eleveUser);

        // Vérifier que l'URL est correcte
        $this->assertStringContainsString('http://localhost:4200/espace-famille', $mailMessage->actionUrl);
        $this->assertEquals('Consulter ton bulletin', $mailMessage->actionText);
    }

    public function test_notification_urls_use_configuration()
    {
        // Tester avec une URL différente
        config(['notifications.welcome.frontend_url' => 'https://mon-ecole.com']);
        
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

        $notification = new EleveWelcomeNotification('password123');
        $mailMessage = $notification->toMail($eleveUser);

        // Vérifier que l'URL utilise la configuration
        $this->assertStringContainsString('https://mon-ecole.com/login', $mailMessage->actionUrl);
    }

    public function test_notification_urls_are_not_laravel_urls()
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

        $notification = new EleveWelcomeNotification('password123');
        $mailMessage = $notification->toMail($eleveUser);

        // Vérifier que l'URL n'est pas une URL Laravel
        $this->assertStringNotContainsString('127.0.0.1:8000', $mailMessage->actionUrl);
        $this->assertStringNotContainsString('localhost:8000', $mailMessage->actionUrl);
        $this->assertStringNotContainsString('/api/', $mailMessage->actionUrl);
    }
} 