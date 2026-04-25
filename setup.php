<?php
require_once 'api/config.php';

$errors   = [];
$messages = [];

try {
    $db = getDB();

    // ── Schema ────────────────────────────────────────────────────────────────
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");

    $db->exec("CREATE TABLE IF NOT EXISTS agents (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        nom       VARCHAR(200) NOT NULL,
        role_label VARCHAR(200),
        pole      VARCHAR(100) DEFAULT 'Autre',
        extension VARCHAR(20),
        email     VARCHAR(200),
        initiales VARCHAR(5),
        couleur   VARCHAR(20) DEFAULT '#1B3A7A',
        actif     TINYINT(1)  DEFAULT 1,
        ordre     INT         DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS contacts (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        nom       VARCHAR(200) NOT NULL,
        role_label VARCHAR(200),
        extension VARCHAR(20),
        email     VARCHAR(200),
        couleur   VARCHAR(20) DEFAULT '#1B3A7A',
        actif     TINYINT(1)  DEFAULT 1,
        ordre     INT         DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS faq (
        id       INT AUTO_INCREMENT PRIMARY KEY,
        question TEXT NOT NULL,
        reponse  TEXT,
        actif    TINYINT(1) DEFAULT 1,
        ordre    INT        DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS procedures (
        id      INT AUTO_INCREMENT PRIMARY KEY,
        titre   VARCHAR(500) NOT NULL,
        tag     VARCHAR(100),
        couleur VARCHAR(20) DEFAULT '#00A8D6',
        actif   TINYINT(1)  DEFAULT 1,
        ordre   INT         DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS procedure_etapes (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        procedure_id INT NOT NULL,
        etape        TEXT NOT NULL,
        ordre        INT  DEFAULT 0,
        FOREIGN KEY (procedure_id) REFERENCES procedures(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS outils_categories (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        categorie VARCHAR(200) NOT NULL,
        icone     VARCHAR(100) DEFAULT 'tool',
        couleur   VARCHAR(20)  DEFAULT '#00A8D6',
        actif     TINYINT(1)   DEFAULT 1,
        ordre     INT          DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS outils_items (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        categorie_id INT NOT NULL,
        nom         VARCHAR(300) NOT NULL,
        ordre       INT DEFAULT 0,
        FOREIGN KEY (categorie_id) REFERENCES outils_categories(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS ressources_categories (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        categorie VARCHAR(200) NOT NULL,
        icone     VARCHAR(100) DEFAULT 'link',
        couleur   VARCHAR(20)  DEFAULT '#00A8D6',
        actif     TINYINT(1)   DEFAULT 1,
        ordre     INT          DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS ressources_liens (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        categorie_id INT NOT NULL,
        label        VARCHAR(300) NOT NULL,
        description  VARCHAR(300),
        url          VARCHAR(500) DEFAULT '#',
        ordre        INT          DEFAULT 0,
        FOREIGN KEY (categorie_id) REFERENCES ressources_categories(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS horaires (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        jour         VARCHAR(200) NOT NULL,
        horaire      VARCHAR(200),
        type_horaire VARCHAR(200),
        couleur      VARCHAR(20) DEFAULT '#5CB85C',
        ordre        INT         DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS infos_pratiques (
        id      INT AUTO_INCREMENT PRIMARY KEY,
        titre   VARCHAR(200) NOT NULL,
        contenu TEXT,
        icone   VARCHAR(100) DEFAULT 'info',
        couleur VARCHAR(20)  DEFAULT '#00A8D6',
        ordre   INT          DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS org_nodes (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        parent_id INT          NULL,
        nom       VARCHAR(200) NOT NULL,
        role_label VARCHAR(200),
        initiales VARCHAR(5),
        couleur   VARCHAR(20) DEFAULT '#1B3A7A',
        ordre     INT         DEFAULT 0,
        INDEX (parent_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("SET FOREIGN_KEY_CHECKS = 1");
    $messages[] = "Tables créées avec succès.";

    // ── Seed data (only if tables are empty) ─────────────────────────────────

    // Agents
    if ($db->query("SELECT COUNT(*) FROM agents")->fetchColumn() == 0) {
        $agents = [
            ['Martin Dupont',   'Directeur DSN',           'Direction',      '5040', 'm.dupont@chu-angers.fr',      'MD', '#1B3A7A'],
            ['Sophie Laurent',  'Responsable Adjointe',    'Direction',      '5041', 's.laurent@chu-angers.fr',     'SL', '#1B3A7A'],
            ['Karim Benali',    'Responsable Infra',       'Infrastructure', '5010', 'k.benali@chu-angers.fr',      'KB', '#00A8D6'],
            ['Ingrid Morel',    'Ingénieure Système',      'Infrastructure', '5011', 'i.morel@chu-angers.fr',       'IM', '#00A8D6'],
            ['Thomas Roux',     'Technicien Réseau',       'Infrastructure', '5012', 't.roux@chu-angers.fr',        'TR', '#00A8D6'],
            ['Amara Diallo',    'Administratrice Système', 'Infrastructure', '5013', 'a.diallo@chu-angers.fr',      'AD', '#00A8D6'],
            ['Julie Petit',     'Responsable Applications','Applications',   '5020', 'j.petit@chu-angers.fr',       'JP', '#8A6FE8'],
            ['Romain Garnier',  'Chef de projet SI',       'Applications',   '5021', 'r.garnier@chu-angers.fr',     'RG', '#8A6FE8'],
            ['Léa Fontaine',    'Développeuse',            'Applications',   '5022', 'l.fontaine@chu-angers.fr',    'LF', '#8A6FE8'],
            ['Hugo Bernard',    'Intégrateur',             'Applications',   '5023', 'h.bernard@chu-angers.fr',     'HB', '#8A6FE8'],
            ['Nadia Chevalier', 'Responsable Support',     'Support',        '5030', 'n.chevalier@chu-angers.fr',   'NC', '#F5A020'],
            ['Pierre Marchand', 'Technicien N1',           'Support',        '5031', 'p.marchand@chu-angers.fr',    'PM', '#F5A020'],
            ['Clara Vidal',     'Technicienne N1',         'Support',        '5032', 'c.vidal@chu-angers.fr',       'CV', '#F5A020'],
            ['Antoine Lefèvre', 'Technicien N2',           'Support',        '5033', 'a.lefevre@chu-angers.fr',     'AL', '#F5A020'],
            ['Yasmine Touati',  'RSSI',                    'Sécurité',       '5050', 'y.touati@chu-angers.fr',      'YT', '#D63030'],
            ['Benoît Tissier',  'Analyste Sécurité',       'Sécurité',       '5051', 'b.tissier@chu-angers.fr',     'BT', '#D63030'],
        ];
        $stmt = $db->prepare("INSERT INTO agents (nom,role_label,pole,extension,email,initiales,couleur,ordre) VALUES (?,?,?,?,?,?,?,?)");
        foreach ($agents as $i => $a) {
            $stmt->execute([$a[0],$a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$i]);
        }
        $messages[] = "Agents insérés.";
    }

    // Contacts
    if ($db->query("SELECT COUNT(*) FROM contacts")->fetchColumn() == 0) {
        $contacts = [
            ['Helpdesk DSN',            '1er niveau – support quotidien',      '5000', 'helpdesk@chu-angers.fr',  '#00A8D6'],
            ['Astreinte informatique',   'Urgences hors heures ouvrées',       '5001', 'astreinte@chu-angers.fr', '#D63030'],
            ['Responsable Infra',        'Réseau, serveurs, datacenter',       '5010', 'infra@chu-angers.fr',     '#1B3A7A'],
            ['Responsable Applicatif',   'Logiciels métier & DPI',             '5020', 'apps@chu-angers.fr',      '#8A6FE8'],
            ['RSSI',                     'Sécurité & conformité RGPD',         '5030', 'rssi@chu-angers.fr',      '#F5A020'],
            ['Direction DSN',            'Direction & administration',          '5040', 'dsn@chu-angers.fr',       '#5CB85C'],
        ];
        $stmt = $db->prepare("INSERT INTO contacts (nom,role_label,extension,email,couleur,ordre) VALUES (?,?,?,?,?,?)");
        foreach ($contacts as $i => $c) {
            $stmt->execute([$c[0],$c[1],$c[2],$c[3],$c[4],$i]);
        }
        $messages[] = "Contacts insérés.";
    }

    // FAQ
    if ($db->query("SELECT COUNT(*) FROM faq")->fetchColumn() == 0) {
        $faqs = [
            ['Comment accéder à ma messagerie professionnelle ?', "Votre compte Outlook est créé avant votre arrivée. Accès via webmail.chu-angers.fr ou l'application Outlook. Identifiants remis lors de l'accueil. Problème → helpdesk (ext. 5000)."],
            ['Que faire si j\'oublie mon mot de passe ?', "Portail de réinitialisation sur l'intranet, ou appelez le helpdesk (ext. 5000). Ne partagez jamais votre mot de passe."],
            ['Comment signaler une panne urgente hors heures ouvrées ?', "Appelez l'astreinte informatique (ext. 5001, 24h/24). Précisez la nature et l'impact sur les soins."],
            ['Puis-je utiliser mon matériel personnel ?', "Non – le matériel personnel n'est pas autorisé sur le réseau interne. Pour un besoin spécifique, ouvrez une demande via GLPI."],
            ['Comment demander une formation sur un outil ?', "Consultez le plan de formation DSN sur l'intranet et parlez-en à votre responsable."],
            ['Qui contacter pour les questions RH ?', "Contactez la DRH via l'espace RH en ligne ou par téléphone (ext. 4000)."],
        ];
        $stmt = $db->prepare("INSERT INTO faq (question,reponse,ordre) VALUES (?,?,?)");
        foreach ($faqs as $i => $f) {
            $stmt->execute([$f[0],$f[1],$i]);
        }
        $messages[] = "FAQ insérée.";
    }

    // Procédures
    if ($db->query("SELECT COUNT(*) FROM procedures")->fetchColumn() == 0) {
        $procs = [
            ['Déclarer un incident informatique',         'Helpdesk',   '#00A8D6',
             ['Connectez-vous à GLPI','Créer un ticket : Assistance > Créer','Renseignez titre, catégorie, description','Joignez une capture si possible','Validez – réponse sous 4h']],
            ['Demander un accès applicatif',              'Sécurité',   '#D63030',
             ['Faire signer la demande par votre N+1','Déposer dans ServiceNow > Accès','Joindre le formulaire signé','Délai : 48–72h ouvrées']],
            ['Mettre en production une évolution',        'Applicatif', '#8A6FE8',
             ['Ouvrir une RFC dans GLPI','Validation du responsable applicatif','Planifier en CAB (Change Advisory Board)','MEP hors heures de pointe','Renseigner le compte-rendu post-déploiement']],
            ['Signaler un problème de sécurité',          'Sécurité',   '#D63030',
             ['Isoler immédiatement le poste du réseau','Appeler le RSSI (liste intranet)','Ne rien supprimer, ne pas intervenir seul','Ticket GLPI : mention URGENCE SÉCURITÉ']],
            ['Commander du matériel informatique',        'Logistique', '#F5A020',
             ['Compléter le bon de commande (intranet)','Signature N+1','Transmettre à l\'équipe achats DSN','Délai moyen : 2–4 semaines']],
        ];
        $stmtP = $db->prepare("INSERT INTO procedures (titre,tag,couleur,ordre) VALUES (?,?,?,?)");
        $stmtE = $db->prepare("INSERT INTO procedure_etapes (procedure_id,etape,ordre) VALUES (?,?,?)");
        foreach ($procs as $i => $p) {
            $stmtP->execute([$p[0],$p[1],$p[2],$i]);
            $pid = $db->lastInsertId();
            foreach ($p[3] as $j => $e) {
                $stmtE->execute([$pid,$e,$j]);
            }
        }
        $messages[] = "Procédures insérées.";
    }

    // Outils
    if ($db->query("SELECT COUNT(*) FROM outils_categories")->fetchColumn() == 0) {
        $outils = [
            ['Helpdesk',      'ticket',      '#00A8D6', ['GLPI – Incidents','ServiceNow – Demandes']],
            ['Messagerie',    'mail',        '#8A6FE8', ['Outlook / Exchange','Teams','SharePoint']],
            ['Supervision',   'activity',    '#F5A020', ['Centreon','Zabbix','ELK Stack']],
            ['Projet',        'kanban',      '#5CB85C', ['Jira','Confluence']],
            ['Apps cliniques','stethoscope', '#1B3A7A', ['DxCare – DPI','AGFA – Imagerie','Pharma']],
            ['Sécurité',      'lock',        '#D63030', ['CyberArk','Tenable','FortiGate']],
        ];
        $stmtC = $db->prepare("INSERT INTO outils_categories (categorie,icone,couleur,ordre) VALUES (?,?,?,?)");
        $stmtI = $db->prepare("INSERT INTO outils_items (categorie_id,nom,ordre) VALUES (?,?,?)");
        foreach ($outils as $i => $o) {
            $stmtC->execute([$o[0],$o[1],$o[2],$i]);
            $cid = $db->lastInsertId();
            foreach ($o[3] as $j => $item) {
                $stmtI->execute([$cid,$item,$j]);
            }
        }
        $messages[] = "Outils insérés.";
    }

    // Ressources
    if ($db->query("SELECT COUNT(*) FROM ressources_categories")->fetchColumn() == 0) {
        $res = [
            ['Portails',       'globe',     '#00A8D6', [
                ['Intranet CHU Angers',  'Portail agent',       '#'],
                ['Portail DSN',          'Documentation & outils','#'],
                ['GLPI – Helpdesk',      'Créer vos tickets',   '#'],
            ]],
            ['Documentation',  'book-open', '#8A6FE8', [
                ['Wiki technique DSN',   'Procédures internes',    '#'],
                ['Confluence DSN',       'Base de connaissance',   '#'],
                ['Fiches RSSI',          'Bonnes pratiques sécu',  '#'],
            ]],
            ['RH & formation', 'users',     '#F5A020', [
                ['Espace RH en ligne',   'Congés, fiches de paie', '#'],
                ['Plan de formation',    'Formations disponibles', '#'],
                ['Annuaire téléphonique','Tous les postes du CHU', '#'],
            ]],
        ];
        $stmtC = $db->prepare("INSERT INTO ressources_categories (categorie,icone,couleur,ordre) VALUES (?,?,?,?)");
        $stmtL = $db->prepare("INSERT INTO ressources_liens (categorie_id,label,description,url,ordre) VALUES (?,?,?,?,?)");
        foreach ($res as $i => $r) {
            $stmtC->execute([$r[0],$r[1],$r[2],$i]);
            $cid = $db->lastInsertId();
            foreach ($r[3] as $j => $l) {
                $stmtL->execute([$cid,$l[0],$l[1],$l[2],$j]);
            }
        }
        $messages[] = "Ressources insérées.";
    }

    // Horaires
    if ($db->query("SELECT COUNT(*) FROM horaires")->fetchColumn() == 0) {
        $h = [
            ['Lundi – Vendredi',          '7h30 – 18h30',        'Heures ouvrées',    '#5CB85C'],
            ['Samedi',                     '8h00 – 12h00',        'Service réduit',    '#F5A020'],
            ['Dimanche & jours fériés',    'Astreinte uniquement', 'Urgences seulement','#6B7BA8'],
        ];
        $stmt = $db->prepare("INSERT INTO horaires (jour,horaire,type_horaire,couleur,ordre) VALUES (?,?,?,?,?)");
        foreach ($h as $i => $r) {
            $stmt->execute([$r[0],$r[1],$r[2],$r[3],$i]);
        }
        $messages[] = "Horaires insérés.";
    }

    // Infos pratiques
    if ($db->query("SELECT COUNT(*) FROM infos_pratiques")->fetchColumn() == 0) {
        $infos = [
            ['Badge d\'accès',     'Remis par la DRH à la prise de poste. Perte → sécurité ext. 9000.',                     'badge',       '#00A8D6'],
            ['Restauration',       'Self personnel accessible avec badge. Horaires : 11h45–13h45.',                           'utensils',    '#8A6FE8'],
            ['Stationnement',      'Parking agents derrière le bâtiment administratif (badge requis).',                       'car',         '#F5A020'],
            ['Poste de travail',   'PC et téléphone IP configurés avant votre arrivée. Problème → GLPI.',                    'monitor',     '#5CB85C'],
            ['Télétravail',        '1–2 jours/semaine selon accord N+1. VPN fourni. Charte sur intranet.',                   'wifi',        '#1B3A7A'],
            ['Médecine du travail','Visite obligatoire dans les 3 mois. Service santé : ext. 7500.',                          'heart-pulse', '#D63030'],
        ];
        $stmt = $db->prepare("INSERT INTO infos_pratiques (titre,contenu,icone,couleur,ordre) VALUES (?,?,?,?,?)");
        foreach ($infos as $i => $inf) {
            $stmt->execute([$inf[0],$inf[1],$inf[2],$inf[3],$i]);
        }
        $messages[] = "Infos pratiques insérées.";
    }

    // Organigramme
    if ($db->query("SELECT COUNT(*) FROM org_nodes")->fetchColumn() == 0) {
        $stmt = $db->prepare("INSERT INTO org_nodes (parent_id,nom,role_label,initiales,couleur,ordre) VALUES (?,?,?,?,?,?)");

        // Direction
        $stmt->execute([null,'Directeur DSN','Direction générale','DG','#1B3A7A',0]);
        $dir = $db->lastInsertId();
        $stmt->execute([$dir,'Responsable Adjointe','Coordination opérationnelle','RA','#1B3A7A',1]);

        // Pôle Infrastructure
        $stmt->execute([$dir,'Responsable Infrastructure','Pôle Infra','KB','#00A8D6',2]);
        $infra = $db->lastInsertId();
        $stmt->execute([$infra,'Ingénieur Système','Virtualisation & stockage','IS','#00A8D6',0]);
        $is = $db->lastInsertId();
        $stmt->execute([$is,'Tech. Système N2','VMware','AS','#00A8D6',0]);
        $stmt->execute([$is,'Tech. Système N2','Baies & SAN','GF','#00A8D6',1]);
        $stmt->execute([$infra,'Responsable Réseau','LAN / WAN / Wifi','TR','#00A8D6',1]);
        $rn = $db->lastInsertId();
        $stmt->execute([$rn,'Tech. Réseau N2','LAN / Switching','PL','#00A8D6',0]);
        $stmt->execute([$rn,'Tech. Réseau N2','Wifi & VPN','MK','#00A8D6',1]);
        $stmt->execute([$rn,'Tech. Réseau N1','Support réseau','JD','#00A8D6',2]);
        $stmt->execute([$infra,'Admin. Système','Serveurs & Linux','AD','#00A8D6',2]);
        $as2 = $db->lastInsertId();
        $stmt->execute([$as2,'Tech. Infra N2','Linux / Windows','FB','#00A8D6',0]);
        $stmt->execute([$as2,'Tech. Infra N1','Supervision','OT','#00A8D6',1]);

        // Pôle Applications
        $stmt->execute([$dir,'Responsable Applications','Pôle Applicatif','JP','#8A6FE8',3]);
        $apps = $db->lastInsertId();
        $stmt->execute([$apps,'Chef de projet SI','DxCare & DPI','RG','#8A6FE8',0]);
        $rg = $db->lastInsertId();
        $stmt->execute([$rg,'Paramétreuse DPI','Workflows cliniques','EL','#8A6FE8',0]);
        $stmt->execute([$rg,'Intégrateur DPI','Interfaces HL7','SA','#8A6FE8',1]);
        $stmt->execute([$apps,'Chef de projet SI','RH & Finance','ML','#8A6FE8',1]);
        $ml = $db->lastInsertId();
        $stmt->execute([$ml,'Analyste fonctionnel','Module paie','CG','#8A6FE8',0]);
        $stmt->execute([$ml,'Analyste fonctionnel','Module RH','VB','#8A6FE8',1]);
        $stmt->execute([$apps,'Développeuse','Intégrations & API','LF','#8A6FE8',2]);
        $lf = $db->lastInsertId();
        $stmt->execute([$lf,'Développeur junior','Frontend','HB','#8A6FE8',0]);
        $stmt->execute([$lf,'Développeur junior','Backend','WA','#8A6FE8',1]);

        // Pôle Support
        $stmt->execute([$dir,'Responsable Support','Pôle Support & Helpdesk','NC','#F5A020',4]);
        $sup = $db->lastInsertId();
        $stmt->execute([$sup,'Tech. Support N2','Référent poste de travail','AL','#F5A020',0]);
        $al = $db->lastInsertId();
        $stmt->execute([$al,'Tech. Support N1','Helpdesk','PM','#F5A020',0]);
        $stmt->execute([$al,'Tech. Support N1','Helpdesk','CV','#F5A020',1]);
        $stmt->execute([$al,'Tech. Support N1','Helpdesk','BN','#F5A020',2]);
        $stmt->execute([$sup,'Tech. Support N2','Déploiement & mobilité','ER','#F5A020',1]);
        $er = $db->lastInsertId();
        $stmt->execute([$er,'Tech. Support N1','Proximité Nord','TF','#F5A020',0]);
        $stmt->execute([$er,'Tech. Support N1','Proximité Sud','AM','#F5A020',1]);

        // Pôle Sécurité
        $stmt->execute([$dir,'RSSI','Sécurité & Conformité','YT','#D63030',5]);
        $rssi = $db->lastInsertId();
        $stmt->execute([$rssi,'Analyste Sécurité','Pentest & audit','BT','#D63030',0]);
        $stmt->execute([$rssi,'Chargé conformité','RGPD & politiques','FP','#D63030',1]);
        $stmt->execute([$rssi,'Tech. SOC','Surveillance & alertes','MR','#D63030',2]);

        $messages[] = "Organigramme inséré.";
    }

    $messages[] = '<strong>Installation terminée.</strong>';

} catch (PDOException $e) {
    $errors[] = "Erreur PDO : " . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Installation – Livret d'accueil DSN</title>
<style>
  body { font-family: system-ui, sans-serif; max-width: 600px; margin: 60px auto; padding: 0 20px; }
  h1   { color: #1B3A7A; }
  .ok  { background: #d4edda; border: 1px solid #c3e6cb; padding: 10px 14px; border-radius: 8px; margin: 8px 0; color: #155724; }
  .err { background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px 14px; border-radius: 8px; margin: 8px 0; color: #721c24; }
  a.btn { display: inline-block; margin-top: 20px; background: #1B3A7A; color: #fff; padding: 10px 22px; border-radius: 24px; text-decoration: none; font-weight: 700; }
</style>
</head>
<body>
<h1>🛠 Installation – Livret d'accueil DSN</h1>
<?php foreach ($errors   as $e): ?><div class="err">❌ <?= $e ?></div><?php endforeach; ?>
<?php foreach ($messages as $m): ?><div class="ok">✅ <?= $m ?></div><?php endforeach; ?>
<?php if (empty($errors)): ?>
<a class="btn" href="index.php">Accéder au livret →</a>
<a class="btn" href="admin/" style="background:#F5A020; margin-left:10px">Administration →</a>
<?php endif; ?>
</body>
</html>
