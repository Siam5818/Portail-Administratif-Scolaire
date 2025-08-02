# Variables d'Environnement pour les Notifications

## Configuration Requise

Pour que le système de notifications fonctionne correctement, vous devez configurer les variables d'environnement suivantes dans votre fichier `.env` :

### Variables Obligatoires

```env
# Configuration des notifications
MAIL_FROM_ADDRESS=noreply@ecole.com
MAIL_FROM_NAME=Portail Scolaire
FRONTEND_URL=http://localhost:4200

# Configuration email (pour l'envoi des notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### Variables Optionnelles

```env
# Configuration avancée des notifications
NOTIFICATIONS_QUEUE=true
NOTIFICATIONS_RETRY_ATTEMPTS=3
```

## Configuration par Environnement

### Développement Local
```env
FRONTEND_URL=http://localhost:4200
MAIL_MAILER=log
```

### Production
```env
FRONTEND_URL=https://votre-domaine.com
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
```

## Test de Configuration

Pour tester que votre configuration est correcte :

```bash
# Tester les notifications
php artisan notifications:test

# Vérifier la configuration email
php artisan tinker
>>> config('mail')
```

## Problèmes Courants

### URLs Incorrectes
- **Problème** : Les notifications pointent vers `http://127.0.0.1:8000` au lieu du frontend
- **Solution** : Vérifiez que `FRONTEND_URL` est correctement configuré

### Emails non envoyés
- **Problème** : Les notifications ne sont pas envoyées
- **Solution** : Vérifiez la configuration SMTP et les logs Laravel

### URLs incohérentes
- **Problème** : Mélange de `localhost` et `127.0.0.1`
- **Solution** : Utilisez une URL cohérente dans `FRONTEND_URL` 