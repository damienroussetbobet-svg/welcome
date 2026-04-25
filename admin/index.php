<?php
$pageTitle = 'Tableau de bord';
require_once '_layout.php';
require_once '../api/config.php';
$db = getDB();
$stats = [
    'agents'     => $db->query("SELECT COUNT(*) FROM agents WHERE actif=1")->fetchColumn(),
    'contacts'   => $db->query("SELECT COUNT(*) FROM contacts WHERE actif=1")->fetchColumn(),
    'faq'        => $db->query("SELECT COUNT(*) FROM faq WHERE actif=1")->fetchColumn(),
    'procedures' => $db->query("SELECT COUNT(*) FROM procedures WHERE actif=1")->fetchColumn(),
    'org_nodes'  => $db->query("SELECT COUNT(*) FROM org_nodes")->fetchColumn(),
];
?>
<div class="page-header">
  <h1>🏠 Tableau de bord</h1>
  <a href="../index.php" target="_blank" class="btn btn-outline-primary btn-sm">Voir le livret →</a>
</div>

<div class="row g-3 mb-4">
  <?php
  $cards = [
    ['agents.php',       '👤', 'Agents dans l\'annuaire', $stats['agents'],     '#00A8D6'],
    ['organigramme.php', '🏗️', 'Nœuds organigramme',    $stats['org_nodes'],  '#1B3A7A'],
    ['contacts.php',     '📞', 'Contacts utiles',         $stats['contacts'],   '#8A6FE8'],
    ['faq.php',          '❓', 'Questions FAQ',            $stats['faq'],        '#F5A020'],
    ['procedures.php',   '📋', 'Procédures',              $stats['procedures'], '#5CB85C'],
  ];
  foreach ($cards as [$href, $ico, $lbl, $count, $color]):
  ?>
  <div class="col-md-4 col-lg-2-4">
    <a href="<?= $href ?>" class="text-decoration-none">
      <div class="card p-3 h-100" style="border-left:4px solid <?= $color ?>!important">
        <div style="font-size:28px"><?= $ico ?></div>
        <div style="font-size:28px; font-weight:800; color:<?= $color ?>"><?= $count ?></div>
        <div style="font-size:12px; color:#6B7BA8"><?= $lbl ?></div>
      </div>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<div class="row g-3">
  <div class="col-md-6">
    <div class="card p-4">
      <h6 class="fw-bold mb-3">📝 Sections administrables</h6>
      <div class="list-group list-group-flush">
        <?php
        $sections = [
          ['agents.php',       '👤', 'Annuaire / Agents',   'Ajouter, modifier ou supprimer des agents'],
          ['organigramme.php', '🏗️', 'Organigramme',        'Gérer la hiérarchie des postes'],
          ['contacts.php',     '📞', 'Contacts utiles',      'Numéros et emails de référence'],
          ['faq.php',          '❓', 'FAQ',                  'Questions fréquentes et réponses'],
          ['procedures.php',   '📋', 'Procédures',           'Guides pas-à-pas'],
          ['outils.php',       '🛠️', 'Outils & logiciels',  'Environnement de travail'],
          ['ressources.php',   '🔗', 'Ressources & liens',  'Portails et documentation'],
          ['planning.php',     '📅', 'Horaires',             'Heures d\'ouverture'],
          ['pratique.php',     'ℹ️', 'Infos pratiques',     'Badge, parking, restauration…'],
        ];
        foreach ($sections as [$href, $ico, $title, $desc]):
        ?>
        <a href="<?= $href ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 px-0 border-0 border-bottom">
          <span style="font-size:18px;width:24px;text-align:center"><?= $ico ?></span>
          <div>
            <div class="fw-semibold" style="font-size:13.5px"><?= $title ?></div>
            <div class="text-muted" style="font-size:12px"><?= $desc ?></div>
          </div>
          <span class="ms-auto text-muted">›</span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card p-4">
      <h6 class="fw-bold mb-3">⚙️ Accès rapides</h6>
      <div class="d-grid gap-2">
        <a href="../index.php" target="_blank" class="btn btn-outline-primary">🌐 Voir le livret d'accueil</a>
        <a href="../setup.php" class="btn btn-outline-warning">🛠 Réinitialiser les données de démo</a>
        <a href="logout.php" class="btn btn-outline-danger">🚪 Déconnexion</a>
      </div>
      <hr>
      <div class="small text-muted">
        <strong>Mot de passe admin :</strong> modifiable dans <code>api/config.php</code><br>
        <strong>Base de données :</strong> <code>welcome</code> sur localhost:<?= DB_PORT ?>
      </div>
    </div>
  </div>
</div>

<?php require_once '_footer.php'; ?>
