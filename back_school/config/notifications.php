<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration des notifications
    |--------------------------------------------------------------------------
    |
    | Configuration pour les notifications du système
    | de gestion scolaire.
    |
    */

    'email' => [
        'from' => [
            'address' => env('MAIL_FROM_ADDRESS', 'noreply@ecole.com'),
            'name' => env('MAIL_FROM_NAME', 'Portail Scolaire'),
        ],
    ],

    'bulletins' => [
        'notify_eleve' => true,
        'notify_tuteur' => true,
        'include_moyenne' => true,
        'include_mention' => true,
        'frontend_url' => env('FRONTEND_URL', 'http://localhost:4200'),
    ],

    'welcome' => [
        'notify_eleve' => true,
        'notify_tuteur' => true,
        'notify_enseignant' => true,
        'include_password' => true,
        'frontend_url' => env('FRONTEND_URL', 'http://localhost:4200'),
    ],

    'channels' => [
        'default' => ['mail'],
        'urgent' => ['mail', 'database'],
    ],
];
