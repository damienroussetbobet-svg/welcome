<?php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 8889); // MAMP default – change to 3306 for standard MySQL
define('DB_NAME', 'welcome');
define('DB_USER', 'root');
define('DB_PASS', 'root');

define('ADMIN_LOGIN',         'admin');
define('ADMIN_PASSWORD_HASH', '$2y$12$cL7KvvNRzBFSqm4TNVMFle16ma8Z0OIUCas4uGnE8dKgOAPqdAgDe'); // Admin@DSN2025

function loadConfig(): array {
    $rows = getDB()->query("SELECT cle, valeur FROM site_config")->fetchAll();
    $cfg  = [];
    foreach ($rows as $r) $cfg[$r['cle']] = $r['valeur'];
    return $cfg;
}

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

function buildOrgTree(array $nodes, ?int $parentId = null): array {
    $tree = [];
    foreach ($nodes as $node) {
        $nodeParent = $node['parent_id'] === null ? null : (int)$node['parent_id'];
        if ($nodeParent === $parentId) {
            $node['n']        = $node['nom'];
            $node['r']        = $node['role_label'];
            $node['i']        = $node['initiales'];
            $node['ext']      = $node['a_extension']   ?? null;
            $node['poste2']   = $node['a_poste2']      ?? null;
            $node['tel']      = $node['a_numero_long'] ?? null;
            $node['email']    = $node['a_email']       ?? null;
            $node['children'] = buildOrgTree($nodes, (int)$node['id']);
            $tree[]           = $node;
        }
    }
    return $tree;
}

function loadSiteData(): array {
    $db = getDB();

    $agents   = $db->query("SELECT * FROM agents WHERE actif=1 ORDER BY ordre,id")->fetchAll();
    $domaines = $db->query("SELECT * FROM domaines ORDER BY ordre,id")->fetchAll();

    $contacts = $db->query("SELECT * FROM contacts WHERE actif=1 ORDER BY ordre,id")->fetchAll();

    $faq = $db->query("SELECT * FROM faq WHERE actif=1 ORDER BY ordre,id")->fetchAll();

    $procedures = $db->query("SELECT * FROM procedures WHERE actif=1 ORDER BY ordre,id")->fetchAll();
    $etapes     = $db->query("SELECT * FROM procedure_etapes ORDER BY procedure_id,ordre")->fetchAll();
    foreach ($procedures as &$proc) {
        $proc['steps'] = array_values(array_map(
            fn($e) => $e['etape'],
            array_filter($etapes, fn($e) => (int)$e['procedure_id'] === (int)$proc['id'])
        ));
    }
    unset($proc);

    $outils_cats  = $db->query("SELECT * FROM outils_categories WHERE actif=1 ORDER BY ordre,id")->fetchAll();
    $outils_items = $db->query("SELECT * FROM outils_items ORDER BY categorie_id,ordre")->fetchAll();
    foreach ($outils_cats as &$cat) {
        $cat['items'] = array_values(array_map(
            fn($i) => $i['nom'],
            array_filter($outils_items, fn($i) => (int)$i['categorie_id'] === (int)$cat['id'])
        ));
    }
    unset($cat);

    $res_cats  = $db->query("SELECT * FROM ressources_categories WHERE actif=1 ORDER BY ordre,id")->fetchAll();
    $res_liens = $db->query("SELECT * FROM ressources_liens ORDER BY categorie_id,ordre")->fetchAll();
    foreach ($res_cats as &$cat) {
        $cat['links'] = array_values(array_filter($res_liens, fn($l) => (int)$l['categorie_id'] === (int)$cat['id']));
    }
    unset($cat);

    $horaires = $db->query("SELECT * FROM horaires ORDER BY ordre,id")->fetchAll();
    $pratiques = $db->query("SELECT * FROM infos_pratiques ORDER BY ordre,id")->fetchAll();

    $org_nodes = $db->query("
        SELECT n.*,
               a.extension   AS a_extension,
               a.poste2      AS a_poste2,
               a.numero_long AS a_numero_long,
               a.email       AS a_email
        FROM   org_nodes n
        LEFT   JOIN agents a ON a.id = n.agent_id
        ORDER  BY n.ordre, n.id
    ")->fetchAll();
    $org_tree  = buildOrgTree($org_nodes, null);

    return [
        'agents'     => $agents,
        'domaines'   => $domaines,
        'contacts'   => $contacts,
        'faq'        => $faq,
        'procedures' => $procedures,
        'outils'     => $outils_cats,
        'ressources' => $res_cats,
        'horaires'   => $horaires,
        'pratiques'  => $pratiques,
        'org_tree'   => $org_tree[0] ?? null,
        'config'     => loadConfig(),
    ];
}
