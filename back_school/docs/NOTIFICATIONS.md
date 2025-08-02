# Système de Notifications

## Vue d'ensemble

Le système de notifications du portail scolaire permet d'informer automatiquement les utilisateurs des événements importants via email.

## Types de Notifications

### 1. Notifications de Bienvenue

#### Élève Welcome Notification
- **Déclencheur** : Création d'un nouvel élève
- **Destinataire** : L'élève
- **Contenu** :
  - Salutation personnalisée
  - Informations de connexion (email, mot de passe)
  - Lien vers le portail
  - Instructions de changement de mot de passe

#### Tuteur Welcome Notification
- **Déclencheur** : Création d'un nouveau tuteur
- **Destinataire** : Le tuteur
- **Contenu** :
  - Salutation personnalisée
  - Informations de connexion
  - Lien vers le portail
  - Instructions de changement de mot de passe

#### Enseignant Welcome Notification
- **Déclencheur** : Création d'un nouvel enseignant
- **Destinataire** : L'enseignant
- **Contenu** :
  - Salutation personnalisée
  - Informations de connexion
  - Lien vers l'espace enseignant

### 2. Notifications de Bulletin

#### Bulletin Disponible Notification (Élève)
- **Déclencheur** : Génération d'un nouveau bulletin
- **Destinataire** : L'élève
- **Contenu** :
  - Information sur la disponibilité du bulletin
  - Moyenne générale
  - Mention obtenue
  - Lien vers l'espace famille
  - Message d'encouragement

#### Bulletin Tuteur Notification
- **Déclencheur** : Génération d'un nouveau bulletin
- **Destinataire** : Le tuteur de l'élève
- **Contenu** :
  - Information sur la disponibilité du bulletin
  - Nom de l'élève
  - Moyenne générale
  - Mention obtenue
  - Lien vers l'espace famille
  - Invitation à consulter et discuter

## Architecture

### Services

#### NotificationService
Service centralisé pour gérer toutes les notifications :

```php
class NotificationService
{
    public function envoyerNotificationEleve(Eleve $eleve, string $password): void
    public function envoyerNotificationTuteur(User $tuteur, string $password): void
    public function envoyerNotificationEnseignant(User $enseignant, string $password): void
    public function envoyerNotificationsBulletin(Bulletin $bulletin): void
    public function envoyerNotificationsCreationEleve(Eleve $eleve, string $passwordEleve, ?string $passwordTuteur = null): void
}
```

#### BulletinService
Intégration automatique des notifications lors de la génération de bulletins.

### Classes de Notification

Chaque type de notification hérite de `Illuminate\Notifications\Notification` :

- `EleveWelcomeNotification`
- `TuteurWelcomeNotification`
- `EnseignantWelcomeNotification`
- `BulletinDisponibleNotification`
- `BulletinTuteurNotification`

## Configuration

### Fichier de configuration
`config/notifications.php` :

```php
return [
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
    // ...
];
```

### Variables d'environnement
```env
MAIL_FROM_ADDRESS=noreply@ecole.com
MAIL_FROM_NAME=Portail Scolaire
FRONTEND_URL=http://localhost:4200
```

## Utilisation

### Dans les contrôleurs

```php
// Dans EleveController
$this->notificationService->envoyerNotificationsCreationEleve(
    $eleve, 
    $defaultPasswordEleve, 
    $result['tuteurPassword'] ?? null
);
```

### Dans les services

```php
// Dans BulletinService
$this->notificationService->envoyerNotificationsBulletin($bulletin);
```

## Tests

### Tests unitaires
- `WelcomeNotificationTest` : Test des notifications de bienvenue
- `BulletinNotificationTest` : Test des notifications de bulletin

### Commande de test
```bash
php artisan notifications:test --type=all
php artisan notifications:test --type=welcome
php artisan notifications:test --type=bulletin
```

## Flux de Notifications

### Création d'un élève
1. Création de l'utilisateur élève
2. Création du profil élève
3. Si nouveau tuteur : création du tuteur
4. Envoi de la notification de bienvenue à l'élève
5. Si nouveau tuteur : envoi de la notification de bienvenue au tuteur

### Génération d'un bulletin
1. Calcul des moyennes
2. Génération du PDF
3. Sauvegarde du bulletin
4. Envoi de la notification à l'élève
5. Envoi de la notification au tuteur

## Personnalisation

### Modifier le contenu des emails
Éditer les méthodes `toMail()` dans les classes de notification.

### Ajouter de nouveaux types de notifications
1. Créer une nouvelle classe de notification
2. Ajouter la méthode dans `NotificationService`
3. Intégrer dans le flux approprié
4. Ajouter les tests correspondants

### Désactiver certaines notifications
Modifier la configuration dans `config/notifications.php`.

## Sécurité

- Vérification de l'existence des emails avant envoi
- Gestion des erreurs d'envoi
- Logs des notifications envoyées
- Protection contre les envois multiples

## Monitoring

### Logs
Les notifications sont loggées pour le suivi :
```php
logger("Notification envoyée à {$user->email}");
```

### Commandes utiles
```bash
# Voir les notifications en attente
php artisan queue:work

# Tester les notifications
php artisan notifications:test

# Voir les logs
tail -f storage/logs/laravel.log
``` 