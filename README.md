# Livret d'accueil DSN — CHU Angers

Application web de livret d'accueil numérique pour la Direction du Système Numérique (DSN) du CHU Angers. Entièrement administrable via un back-office, sans compétences techniques requises.

---

## Stack technique

| Couche | Technologie |
|---|---|
| Serveur | PHP 7.4+ (MAMP / Apache) |
| Base de données | MySQL 5.7+ |
| Frontend | React 18 (via CDN, pas de build) + Babel Standalone |
| UI Admin | Bootstrap 5.3 |
| Icônes | Lucide 0.468 (CDN) |
| Police | Plus Jakarta Sans (Google Fonts) |

---

## Installation

### 1. Prérequis
- MAMP (ou tout serveur Apache + PHP 7.4+ + MySQL)
- Accès à phpMyAdmin ou à un client MySQL

### 2. Base de données
Créer une base `welcome` puis importer le dump :
```bash
mysql -u root -p welcome < database.sql
```
Ou via phpMyAdmin : sélectionner la base → onglet **Importer** → choisir `database.sql`.

### 3. Configuration
Éditer `api/config.php` et adapter :
```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 8889);       // 3306 en production standard
define('DB_NAME', 'welcome');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('ADMIN_PASSWORD', 'admin123'); // À changer impérativement
```

### 4. Dossier uploads
S'assurer que le dossier `uploads/` est accessible en écriture par le serveur web :
```bash
chmod 775 uploads/
```

### 5. Accès
| URL | Description |
|---|---|
| `/intranet/` | Livret (front public) |
| `/intranet/admin/` | Back-office (login requis) |

---

## Maintenance — Back-office

Accès : `/admin/` → mot de passe défini dans `api/config.php`.

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

- **Vidéo de présentation** : uploadée depuis Admin → Bienvenue. Formats acceptés : MP4, WebM, OGG. Taille max : 200 Mo. Le fichier est stocké dans `uploads/`.
- **Photos agents** : gérées depuis Admin → Agents. Formats : JPG, PNG, WebP. Les fichiers sont stockés dans `uploads/`.

### Bloc Helpdesk

Configurable depuis **Admin → FAQ**, bloc en haut de page. Modifie simultanément :
- La sidebar du livret (encart "Une question ?")
- La bannière en bas de la section FAQ

Champs : e-mail, disponibilité, titre de la bannière, texte du bouton.

---

## Structure des fichiers

```
intranet/
├── index.php            # Application principale (React injecté via PHP)
├── setup.php            # Script d'initialisation BDD (à supprimer après usage)
├── database.sql         # Dump de la base de données
├── logo-chu.png         # Logo CHU Angers
├── uploads/             # Fichiers uploadés (vidéos, photos) — non versionné
├── api/
│   ├── config.php       # Config BDD, fonctions partagées (getDB, loadConfig…)
│   └── data.php         # Point d'entrée API JSON (si utilisé)
└── admin/
    ├── _layout.php      # Layout commun (sidebar, entête HTML)
    ├── _footer.php      # Fermeture HTML commune
    ├── login.php        # Authentification
    ├── logout.php       # Déconnexion
    ├── index.php        # Tableau de bord admin
    ├── bienvenue.php    # Section Bienvenue
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
| `site_config` | Paires clé/valeur pour tous les textes configurables |
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

> Points obligatoires à traiter avant toute mise en ligne.

### Sécurité

- [ ] **Mot de passe admin** — Changer `ADMIN_PASSWORD` dans `api/config.php` (valeur actuelle : `admin123`)
- [ ] **Credentials base de données** — Remplacer `root/root` par un utilisateur MySQL dédié avec droits limités (`SELECT, INSERT, UPDATE, DELETE` uniquement sur la base `welcome`)
- [ ] **Supprimer `setup.php`** — Ce fichier crée et peuple les tables ; le laisser accessible est un risque
- [ ] **HTTPS** — Activer un certificat SSL/TLS (Let's Encrypt ou certificat CHU)
- [ ] **Headers de sécurité** — Ajouter dans `.htaccess` : `X-Frame-Options`, `X-Content-Type-Options`, `Content-Security-Policy`

### Configuration serveur

- [ ] **Port MySQL** — Passer `DB_PORT` de `8889` (MAMP) à `3306` (MySQL standard)
- [ ] **Hôte MySQL** — Adapter `DB_HOST` si la BDD est sur un serveur séparé
- [ ] **Permissions `uploads/`** — Vérifier que le dossier est en écriture (`chmod 775`) et non listable (`Options -Indexes` dans `.htaccess`)
- [ ] **Limite d'upload PHP** — Vérifier dans `php.ini` : `upload_max_filesize = 200M` et `post_max_size = 210M` pour les vidéos

### Contenu

- [ ] **Logo** — Remplacer `logo-chu.png` par la version officielle si nécessaire
- [ ] **Textes Bienvenue** — Vérifier subtitle, titre, texte d'accueil et statistiques via Admin → Bienvenue
- [ ] **E-mail helpdesk** — Vérifier l'adresse dans Admin → FAQ → bloc Helpdesk
- [ ] **Agents** — Vérifier que tous les agents sont à jour (photo, poste, coordonnées)
- [ ] **Organigramme** — Valider la hiérarchie et les couleurs de pôles
- [ ] **Procédures** — Valider les étapes de chaque procédure
- [ ] **Ressources** — Vérifier que tous les liens sont actifs

### Optionnel / Recommandé

- [ ] **Restriction d'accès admin** — Limiter `/admin/` par IP ou authentification HTTP en plus du formulaire PHP
- [ ] **Sauvegarde automatique** — Mettre en place un cron de dump MySQL quotidien
- [ ] **Session sécurisée** — Ajouter dans `_layout.php` : `session_set_cookie_params(['secure'=>true,'httponly'=>true,'samesite'=>'Strict'])`
- [ ] **Retirer les CDN publics** — En environnement sans accès internet, héberger React, Babel et Lucide localement

---

## Mettre à jour le dump BDD

Après chaque modification importante du contenu en back-office, regénérer le dump :
```bash
/Applications/MAMP/Library/bin/mysqldump -h 127.0.0.1 -P 8889 -u root -proot welcome > database.sql
git add database.sql && git commit -m "Update database dump" && git push
```
En production (MySQL standard) :
```bash
mysqldump -u <user> -p welcome > database.sql
```
