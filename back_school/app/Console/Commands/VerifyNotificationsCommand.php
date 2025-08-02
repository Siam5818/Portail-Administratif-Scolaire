<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie la configuration des notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Vérification de la configuration des notifications');
        $this->newLine();

        $this->checkFrontendUrl();
        $this->checkEmailConfiguration();
        $this->checkNotificationConfiguration();
        
        $this->newLine();
        $this->info('✅ Vérification terminée !');
    }

    private function checkFrontendUrl()
    {
        $this->info('🌐 Vérification des URLs frontend...');
        
        $welcomeUrl = config('notifications.welcome.frontend_url');
        $bulletinUrl = config('notifications.bulletins.frontend_url');
        
        if ($welcomeUrl === $bulletinUrl) {
            $this->info("   ✅ URLs cohérentes : {$welcomeUrl}");
        } else {
            $this->error("   ❌ URLs incohérentes :");
            $this->error("      Welcome : {$welcomeUrl}");
            $this->error("      Bulletin : {$bulletinUrl}");
        }
        
        if (str_contains($welcomeUrl, '127.0.0.1:8000') || str_contains($welcomeUrl, 'localhost:8000')) {
            $this->error("   ❌ URL pointe vers le backend Laravel : {$welcomeUrl}");
        } else {
            $this->info("   ✅ URL pointe vers le frontend Angular");
        }
        
        $this->newLine();
    }

    private function checkEmailConfiguration()
    {
        $this->info('📧 Vérification de la configuration email...');
        
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');
        $mailer = config('mail.default');
        
        if ($fromAddress && $fromName) {
            $this->info("   ✅ Expéditeur configuré : {$fromName} <{$fromAddress}>");
        } else {
            $this->error("   ❌ Expéditeur non configuré");
        }
        
        if ($mailer) {
            $this->info("   ✅ Mailer configuré : {$mailer}");
        } else {
            $this->error("   ❌ Mailer non configuré");
        }
        
        $this->newLine();
    }

    private function checkNotificationConfiguration()
    {
        $this->info('⚙️ Vérification de la configuration des notifications...');
        
        $welcomeConfig = config('notifications.welcome');
        $bulletinConfig = config('notifications.bulletins');
        
        if ($welcomeConfig['notify_eleve'] && $welcomeConfig['notify_tuteur']) {
            $this->info("   ✅ Notifications de bienvenue activées");
        } else {
            $this->warn("   ⚠️ Certaines notifications de bienvenue sont désactivées");
        }
        
        if ($bulletinConfig['notify_eleve'] && $bulletinConfig['notify_tuteur']) {
            $this->info("   ✅ Notifications de bulletin activées");
        } else {
            $this->warn("   ⚠️ Certaines notifications de bulletin sont désactivées");
        }
        
        $this->newLine();
    }
} 