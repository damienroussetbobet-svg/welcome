# Livret d'accueil DSN — CHU Angers

Application web de livret d'accueil numérique pour la Direction du Système Numérique (DSN) du CHU Angers. Entièrement administrable via un back-office, sans compétences techniques requises.

---

## Stack technique

| Couche | Technologie |
|---|---|
| Serveur web | Apache 2.4 + PHP 7.4+ |
| Base de données | MySQL 5.7+ (serveur dédié séparé) |
| Frontend | React 18 + JSX pré-compilé (pas de build navigateur) |
| UI Admin | Bootstrap 5.3 |
| Icônes | Lucide 0.468 |
| Police | Plus Jakarta Sans |

> Toutes les dépendances (React, Bootstrap, polices) sont hébergées localement dans `assets/`. Aucune connexion internet requise à l'exécution.

---

## Architecture de déploiement

```
┌─────────────────────────┐        ┌─────────────────────────┐
│   Serveur web (Linux)   │        │   Serveur BDD (Linux)   │
│                         │        │                         │
│  Apache 2.4             │  TCP   │  MySQL 5.7+             │
│  PHP 7.4+               │◄──────►│  Port 3306              │
│  /var/www/intranet/     │  3306  │  Base : welcome         │
│                         │        │  User : intranet_user   │
└─────────────────────────┘        └─────────────────────────┘
```

---

## Installation sur serveur Linux

### 1. Prérequis serveur web

```bash
# Debian / Ubuntu
apt update
apt install -y apache2 php7.4 php7.4-mysql php7.4-mbstring php7.4-json php7.4-fileinfo

# Activer les modules Apache nécessaires
a2enmod rewrite deflate headers expires
systemctl restart apache2
```

Vérifier la version PHP :
```bash
php -v   # doit afficher 7.4.x ou supérieur
```

Extensions PHP requises : `pdo_mysql`, `mbstring`, `json`, `fileinfo`.

---

### 2. Déploiement des fichiers

```bash
# Cloner le dépôt dans le répertoire web
git clone https://github.com/damienroussetbobet-svg/welcome.git /var/www/intranet

# Donner les droits d'écriture sur le dossier uploads à Apache
chown -R www-data:www-data /var/www/intranet/uploads
chmod 775 /var/www/intranet/uploads

# Protéger le fichier de configuration
chmod 640 /var/www/intranet/api/config.php
chown root:www-data /var/www/intranet/api/config.php
```

---

### 3. Configuration Apache — VirtualHost

Créer le fichier `/etc/apache2/sites-available/intranet.conf` :

```apache
<VirtualHost *:80>
    ServerName intranet.chu-angers.fr
    DocumentRoot /var/www/intranet

    <Directory /var/www/intranet>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Logs
    ErrorLog  ${APACHE_LOG_DIR}/intranet_error.log
    CustomLog ${APACHE_LOG_DIR}/intranet_access.log combined
</VirtualHost>
```

Activer le site :
```bash
a2ensite intranet.conf
systemctl reload apache2
```

> Si l'application est accessible via HTTPS, remplacer `*:80` par `*:443` et ajouter les directives SSL (`SSLCertificateFile`, `SSLCertificateKeyFile`).

---

### 4. Configuration PHP

Éditer `/etc/php/7.4/apache2/php.ini` (adapter le chemin selon la distribution) :

```ini
; Uploads vidéo (max 200 Mo)
upload_max_filesize = 200M
post_max_size       = 210M
max_execution_time  = 300
max_input_time      = 300

; Fuseau horaire
date.timezone = Europe/Paris
```

Redémarrer Apache après modification :
```bash
systemctl restart apache2
```

---

### 5. Préparation de la base de données (sur le serveur BDD)

Se connecter au serveur MySQL, puis :

```sql
-- Créer la base
CREATE DATABASE welcome CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Créer un utilisateur dédié avec droits limités
-- Remplacer '10.0.0.1' par l'IP réelle du serveur web
CREATE USER 'intranet_user'@'10.0.0.1' IDENTIFIED BY 'MotDePasseForte!2025';
GRANT SELECT, INSERT, UPDATE, DELETE ON welcome.* TO 'intranet_user'@'10.0.0.1';
FLUSH PRIVILEGES;
```

Importer le dump depuis le serveur web :
```bash
mysql -h <IP_SERVEUR_BDD> -u intranet_user -p welcome < /var/www/intranet/database.sql
```

> Vérifier que le port 3306 est ouvert entre les deux serveurs (règle firewall / pare-feu CHU).

---

### 6. Configuration de la connexion BDD

Éditer `/var/www/intranet/api/config.php` :

```php
define('DB_HOST', '10.0.0.2');      // IP ou hostname du serveur BDD
define('DB_PORT', 3306);            // Port MySQL standard
define('DB_NAME', 'welcome');
define('DB_USER', 'intranet_user'); // Utilisateur dédié (pas root)
define('DB_PASS', 'MotDePasseForte!2025');

define('ADMIN_LOGIN',         'admin');
define('ADMIN_PASSWORD_HASH', '$2y$12$...'); // Hash généré via change_password
```

---

### 7. Première connexion et sécurisation

Accéder à l'interface d'administration :
```
http://intranet.chu-angers.fr/admin/
```

Identifiants par défaut (à changer immédiatement) :
- Login : `admin`
- Mot de passe : `Admin@DSN2025`

**Changer le mot de passe** depuis Admin → 🔐 Identifiants.

Règles de complexité imposées : 10 caractères min., majuscule, minuscule, chiffre, caractère spécial.

---

### 8. Supprimer setup.php

Ce script crée et initialise les tables. Il doit être supprimé après la première installation :

```bash
rm /var/www/intranet/setup.php
```

---

## Modifier le code React (workflow build)

Le code source React est dans `app-source.jsx`. Après toute modification :

```bash
# Sur un poste de développement avec Node.js (pas nécessaire sur le serveur)
cd /chemin/vers/intranet
npm install          # une seule fois
./build.sh           # recompile app-source.jsx → assets/js/app.js
```

Puis déployer `assets/js/app.js` sur le serveur (git push + git pull, ou copie directe).

> Node.js n'est **pas** requis sur le serveur de production. Seul `assets/js/app.js` (JS pré-compilé) est nécessaire.

---

## Maintenance — Back-office

Accès : `/admin/` → identifiants définis via Admin → 🔐 Identifiants.

### Pages d'administration

| Page | Contenu géré |
|---|---|
| **Bienvenue** | Titre, sous-titre, texte d'accueil, bouton CTA, vidéo de présentation, 3 statistiques |
| **Agents** | Annuaire : photo, nom, rôle, pôle, email, téléphone, bureau |
| **Organigramme** | Arbre hiérarchique (nœuds parent/enfant, couleur, initiales) |
| **Contacts** | Contacts utiles : intitulé, email, téléphone, description |
| **FAQ** | Questions/réponses + paramètres du bloc Helpdesk |
| **Procédures** | Guides étape par étape (titre, tag couleur, liste d'étapes) |
| **Outils** | Catégories d'outils et liste d'items par catégorie |
| **Ressources** | Liens utiles groupés par catégorie (label, description, URL) |
| **Planning** | Horaires et plannings d'astreinte |
| **Infos pratiques** | Informations pratiques (parking, restauration, accès…) |

### Gestion des médias

- **Vidéo de présentation** : Admin → Bienvenue. Formats : MP4, WebM, OGG. Max 200 Mo. Stockée dans `uploads/`.
- **Photos agents** : Admin → Agents. Formats : JPG, PNG, WebP. Stockées dans `uploads/`.

### Bloc Helpdesk

Configurable depuis **Admin → FAQ**, bloc en haut de page. Met à jour simultanément la sidebar et la bannière FAQ.

---

## Structure des fichiers

```
intranet/
├── index.php            # Point d'entrée — injecte les données PHP + charge app.js
├── app-source.jsx       # Code source React (JSX) — éditer ici, puis ./build.sh
├── build.sh             # Script de compilation JSX → app.js (poste dev uniquement)
├── setup.php            # Init BDD — À SUPPRIMER après installation
├── database.sql         # Dump MySQL à importer lors du déploiement
├── logo-chu.png         # Logo CHU Angers
├── .htaccess            # Gzip, cache navigateur, headers sécurité
├── package.json         # Dépendances de build (Babel — poste dev uniquement)
├── uploads/             # Fichiers uploadés — non versionné, écriture www-data
├── assets/
│   ├── css/
│   │   ├── bootstrap.min.css   # Bootstrap 5.3 (local)
│   │   └── fonts.css           # Déclarations @font-face locales
│   ├── fonts/
│   │   ├── plus-jakarta-sans-latin.woff2
│   │   └── plus-jakarta-sans-latin-ext.woff2
│   └── js/
│       ├── app.js                      # App React compilée (généré par build.sh)
│       ├── react.production.min.js     # React 18 (local)
│       ├── react-dom.production.min.js # ReactDOM 18 (local)
│       ├── lucide.min.js               # Icônes Lucide (local)
│       └── bootstrap.bundle.min.js     # Bootstrap JS (local)
├── api/
│   └── config.php       # Config BDD + identifiants admin + fonctions partagées
└── admin/
    ├── _layout.php      # Layout commun (sidebar, session, timeout)
    ├── _footer.php      # Fermeture HTML commune
    ├── login.php        # Auth : CSRF + brute-force + bcrypt
    ├── logout.php       # Déconnexion propre
    ├── change_password.php  # Changement identifiants avec validation complexité
    ├── index.php        # Tableau de bord
    ├── bienvenue.php    # Section Bienvenue + upload vidéo
    ├── agents.php       # Annuaire agents
    ├── organigramme.php # Organigramme
    ├── contacts.php     # Contacts
    ├── faq.php          # FAQ + config helpdesk
    ├── procedures.php   # Procédures
    ├── outils.php       # Outils
    ├── ressources.php   # Ressources
    ├── planning.php     # Planning
    └── pratique.php     # Infos pratiques
```

---

## Base de données — Tables

| Table | Description |
|---|---|
| `site_config` | Paires clé/valeur : textes configurables + identifiants admin |
| `agents` | Annuaire des agents DSN |
| `org_nodes` | Nœuds de l'organigramme (auto-référentiel via `parent_id`) |
| `contacts` | Contacts utiles |
| `faq` | Questions fréquentes |
| `procedures` | Procédures / guides |
| `procedure_etapes` | Étapes liées à chaque procédure |
| `outils_categories` | Catégories d'outils |
| `outils_items` | Items par catégorie d'outils |
| `ressources_categories` | Catégories de ressources |
| `ressources_liens` | Liens par catégorie |
| `horaires` | Plannings / horaires |
| `infos_pratiques` | Informations pratiques |

---

## Mise en production — Checklist

> Points obligatoires avant la mise en service.

### Sécurité

- [ ] **Mot de passe admin** — Changer immédiatement via Admin → 🔐 Identifiants (défaut : `Admin@DSN2025`)
- [ ] **Utilisateur MySQL dédié** — Ne pas utiliser `root` ; créer `intranet_user` avec droits `SELECT, INSERT, UPDATE, DELETE` uniquement
- [ ] **Supprimer `setup.php`** — `rm /var/www/intranet/setup.php`
- [ ] **HTTPS** — Configurer un certificat SSL/TLS (Let's Encrypt ou certificat CHU) et forcer la redirection HTTP → HTTPS
- [ ] **Firewall BDD** — Vérifier que le port 3306 est accessible uniquement depuis l'IP du serveur web

### Configuration serveur

- [ ] **`DB_HOST`** — Renseigner l'IP ou hostname du serveur BDD dans `api/config.php`
- [ ] **`DB_PORT`** — Utiliser `3306` (port MySQL standard)
- [ ] **`DB_USER` / `DB_PASS`** — Utiliser les credentials de l'utilisateur dédié
- [ ] **Permissions `uploads/`** — `chown -R www-data:www-data uploads/ && chmod 775 uploads/`
- [ ] **PHP `upload_max_filesize`** — Vérifier `200M` dans `php.ini` pour les vidéos
- [ ] **`AllowOverride All`** — Requis dans le VirtualHost pour que `.htaccess` soit pris en compte

### Contenu

- [ ] **Logo** — Remplacer `logo-chu.png` si nécessaire
- [ ] **Textes Bienvenue** — Vérifier via Admin → Bienvenue
- [ ] **E-mail helpdesk** — Vérifier via Admin → FAQ → bloc Helpdesk
- [ ] **Agents** — Vérifier que l'annuaire est à jour
- [ ] **Organigramme** — Valider la hiérarchie
- [ ] **Procédures / Ressources** — Vérifier les liens et les étapes

### Recommandé

- [ ] **Restriction IP `/admin/`** — Limiter l'accès au back-office aux seules IP internes via `.htaccess` ou VirtualHost
- [ ] **Sauvegarde BDD automatique** — Cron quotidien sur le serveur BDD : `mysqldump -u intranet_user -p welcome > /backups/welcome_$(date +%Y%m%d).sql`
- [ ] **Logs Apache** — Vérifier que les logs sont en place et que la rotation est configurée (`logrotate`)

---

## Mettre à jour le dump BDD

Après des modifications importantes de contenu, régénérer le dump depuis le serveur web :

```bash
mysqldump -h <IP_SERVEUR_BDD> -u intranet_user -p welcome > database.sql
git add database.sql && git commit -m "Update database dump" && git push
```

Sur le serveur de production pour récupérer les mises à jour du code :

```bash
cd /var/www/intranet
git pull
# Si app-source.jsx a changé, redéployer assets/js/app.js (via git pull)
```

---

## Installation POC / Démonstration — Windows + XAMPP

> Cette procédure est destinée à un environnement de démonstration local sur Windows. La base de données est hébergée directement dans XAMPP (pas de serveur séparé).

### Architecture POC

```
┌──────────────────────────────────────────┐
│           PC Windows (XAMPP)             │
│                                          │
│  Apache 2.4   →   C:\xampp\htdocs\      │
│  PHP 7.4+                                │
│  MySQL 5.7+   →   localhost:3306         │
└──────────────────────────────────────────┘
```

---

### 1. Installer XAMPP

Télécharger XAMPP **7.4.x** (PHP 7.4) depuis [apachefriends.org](https://www.apachefriends.org).

> Choisir impérativement la version **7.4** pour rester compatible avec l'application.

Installer dans `C:\xampp` (chemin par défaut recommandé).

Lancer le **XAMPP Control Panel** et démarrer **Apache** et **MySQL**.

---

### 2. Déployer les fichiers

Ouvrir un terminal (PowerShell ou Git Bash) :

```powershell
cd C:\xampp\htdocs
git clone https://github.com/damienroussetbobet-svg/welcome.git intranet
```

Ou, sans Git : télécharger le ZIP depuis GitHub → extraire dans `C:\xampp\htdocs\intranet\`.

---

### 3. Créer la base de données

Ouvrir phpMyAdmin : [http://localhost/phpmyadmin](http://localhost/phpmyadmin)

1. Cliquer **Nouvelle base de données**
2. Nom : `welcome` — Interclassement : `utf8mb4_unicode_ci` → **Créer**
3. Sélectionner la base `welcome` → onglet **Importer**
4. Choisir le fichier `C:\xampp\htdocs\intranet\database.sql` → **Exécuter**

---

### 4. Configurer la connexion BDD

Éditer `C:\xampp\htdocs\intranet\api\config.php` avec un éditeur de texte (Notepad++, VS Code…) :

```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);        // Port XAMPP standard
define('DB_NAME', 'welcome');
define('DB_USER', 'root');      // Utilisateur XAMPP par défaut
define('DB_PASS', '');          // Mot de passe vide par défaut dans XAMPP
```

---

### 5. Activer les modules Apache

Ouvrir `C:\xampp\apache\conf\httpd.conf` et vérifier que ces lignes sont **décommentées** (sans `#`) :

```apache
LoadModule deflate_module modules/mod_deflate.so
LoadModule expires_module  modules/mod_expires.so
LoadModule headers_module  modules/mod_headers.so
LoadModule rewrite_module  modules/mod_rewrite.so
```

Chercher le bloc `<Directory "C:/xampp/htdocs">` et s'assurer que `AllowOverride` est bien à `All` :

```apache
<Directory "C:/xampp/htdocs">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```

Redémarrer Apache depuis le XAMPP Control Panel après toute modification.

---

### 6. Configurer PHP pour les uploads vidéo

Ouvrir `C:\xampp\php\php.ini` et modifier :

```ini
upload_max_filesize = 200M
post_max_size       = 210M
max_execution_time  = 300
max_input_time      = 300
date.timezone       = Europe/Paris
```

Redémarrer Apache après modification.

---

### 7. Accéder à l'application

| URL | Description |
|---|---|
| `http://localhost/intranet/` | Livret d'accueil |
| `http://localhost/intranet/admin/` | Back-office |

Identifiants par défaut :
- Login : `admin`
- Mot de passe : `Admin@DSN2025`

---

### 8. Supprimer setup.php après la première installation

```powershell
del C:\xampp\htdocs\intranet\setup.php
```

---

### Dépannage XAMPP courant

| Symptôme | Cause probable | Solution |
|---|---|---|
| Page blanche ou erreur 500 | Module Apache manquant | Vérifier `httpd.conf`, décommenter `mod_rewrite` et `mod_headers` |
| Erreur connexion BDD | Mauvais port ou mot de passe | Vérifier `DB_PORT = 3306` et `DB_PASS = ''` dans `config.php` |
| Upload vidéo échoue | Limite PHP trop basse | Vérifier `upload_max_filesize = 200M` dans `php.ini` |
| `.htaccess` ignoré | `AllowOverride None` | Passer à `AllowOverride All` dans `httpd.conf` |
| Port 80 déjà utilisé | IIS ou Skype actif | Changer le port Apache dans XAMPP (ex: 8080) ou désactiver IIS |

> **Port 80 occupé sur Windows ?** Dans le XAMPP Control Panel → Config Apache → `httpd.conf`, remplacer `Listen 80` par `Listen 8080`. L'app sera alors accessible sur `http://localhost:8080/intranet/`.
