<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Eleve;
use App\Models\Bulletin;
use App\Services\NotificationService;
use App\Services\BulletinService;

class TestNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:test {--type=all : Type de notification à tester (welcome, bulletin, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Teste les notifications du système';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService, BulletinService $bulletinService)
    {
        $type = $this->option('type');

        $this->info('🧪 Test des notifications du système scolaire');
        $this->newLine();

        if ($type === 'welcome' || $type === 'all') {
            $this->testWelcomeNotifications($notificationService);
        }

        if ($type === 'bulletin' || $type === 'all') {
            $this->testBulletinNotifications($notificationService, $bulletinService);
        }

        $this->info('✅ Tests terminés !');
    }

    private function testWelcomeNotifications(NotificationService $notificationService)
    {
        $this->info('📧 Test des notifications de bienvenue...');

        // Trouver un élève existant
        $eleve = Eleve::with(['user', 'tuteur.user'])->first();

        if (!$eleve) {
            $this->warn('❌ Aucun élève trouvé dans la base de données');
            return;
        }

        $this->info("   - Élève: {$eleve->user->prenom} {$eleve->user->nom} ({$eleve->user->email})");

        if ($eleve->tuteur) {
            $this->info("   - Tuteur: {$eleve->tuteur->user->prenom} {$eleve->tuteur->user->nom} ({$eleve->tuteur->user->email})");
        }

        // Simuler l'envoi des notifications
        try {
            $notificationService->envoyerNotificationsCreationEleve(
                $eleve,
                'password123',
                'tuteurPassword123'
            );

            $this->info('   ✅ Notifications de bienvenue envoyées avec succès');
        } catch (\Exception $e) {
            $this->error("   ❌ Erreur lors de l'envoi: {$e->getMessage()}");
        }

        $this->newLine();
    }

    private function testBulletinNotifications(NotificationService $notificationService, BulletinService $bulletinService)
    {
        $this->info('📊 Test des notifications de bulletin...');

        // Trouver un bulletin existant
        $bulletin = Bulletin::with(['eleve.user', 'eleve.tuteur.user'])->first();

        if (!$bulletin) {
            $this->warn('❌ Aucun bulletin trouvé dans la base de données');
            return;
        }

        $eleve = $bulletin->eleve;
        $this->info("   - Élève: {$eleve->user->prenom} {$eleve->user->nom}");
        $this->info("   - Période: {$bulletin->periode} {$bulletin->annee}");
        $this->info("   - Moyenne: {$bulletin->calculMoyenne()}/20");
        $this->info("   - Mention: {$bulletin->mention}");

        if ($eleve->tuteur) {
            $this->info("   - Tuteur: {$eleve->tuteur->user->prenom} {$eleve->tuteur->user->nom}");
        }

        // Simuler l'envoi des notifications
        try {
            $notificationService->envoyerNotificationsBulletin($bulletin);
            $this->info('   ✅ Notifications de bulletin envoyées avec succès');
        } catch (\Exception $e) {
            $this->error("   ❌ Erreur lors de l'envoi: {$e->getMessage()}");
        }

        $this->newLine();
    }
} 