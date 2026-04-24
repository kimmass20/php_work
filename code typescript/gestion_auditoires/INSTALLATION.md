# Guide d'Installation

## Prérequis

- PHP 7.4 ou supérieur
- Serveur web (Apache/Nginx)
- Permissions d'écriture sur le dossier `data/`

## Installation Rapide

### 1. Télécharger le projet

```bash
cd /var/www/html/
# ou le dossier de votre serveur web
git clone <votre-repo> gestion_auditoires
# ou décompresser le fichier ZIP
```

### 2. Définir les permissions

```bash
cd gestion_auditoires
chmod -R 755 .
chmod -R 777 data/
```

### 3. Configuration Apache

Créer un fichier de configuration Apache (optionnel):

```bash
sudo nano /etc/apache2/sites-available/gestion-auditoires.conf
```

Ajouter:

```apache
<VirtualHost *:80>
    ServerName gestion-auditoires.local
    DocumentRoot /var/www/html/gestion_auditoires

    <Directory /var/www/html/gestion_auditoires>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/gestion-auditoires-error.log
    CustomLog ${APACHE_LOG_DIR}/gestion-auditoires-access.log combined
</VirtualHost>
```

Activer le site:

```bash
sudo a2ensite gestion-auditoires.conf
sudo systemctl reload apache2
```

### 4. Accéder à l'application

Ouvrir votre navigateur et aller à:

```
http://localhost/gestion_auditoires/
```

ou si vous avez configuré un VirtualHost:

```
http://gestion-auditoires.local/
```

## Configuration Nginx (Alternative)

```nginx
server {
    listen 80;
    server_name gestion-auditoires.local;
    root /var/www/html/gestion_auditoires;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~* \.(json)$ {
        deny all;
    }
}
```

## Vérification

1. **Tester la page d'accueil**: Devrait afficher le Dashboard
2. **Ajouter une salle**: Tester le formulaire d'ajout
3. **Vérifier les permissions**: Le dossier `data/` doit être accessible en écriture

## Dépannage

### Erreur: Permission denied

```bash
sudo chown -R www-data:www-data gestion_auditoires/
chmod -R 777 gestion_auditoires/data/
```

### Erreur: Page blanche

Activer l'affichage des erreurs dans `php.ini`:

```ini
display_errors = On
error_reporting = E_ALL
```

### Erreur 404 sur les routes

Vérifier que le module `mod_rewrite` est activé:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## Test de l'Application

1. Aller sur le Dashboard
2. Ajouter une salle (ex: A101, capacité 50)
3. Ajouter une promotion (ex: L1, 45 étudiants)
4. Ajouter un cours (ex: Mathématiques, Obligatoire, L1)
5. Générer le planning
6. Consulter le planning généré

## Support

Pour toute question ou problème, consulter le fichier README.md ou créer une issue.
