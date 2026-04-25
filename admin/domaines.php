<?php
$pageTitle = 'Domaines';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = '';
$err = '';

$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

// ── Suppression
if ($action === 'delete' && $id) {
    $stmt = $db->prepare("SELECT nom FROM domaines WHERE id=?");
    $stmt->execute([$id]);
    $nomDom = $stmt->fetchColumn();

    $stmt2 = $db->prepare("SELECT COUNT(*) FROM agents WHERE pole=?");
    $stmt2->execute([$nomDom]);
    $nbAgents = (int)$stmt2->fetchColumn();

    if ($nbAgents > 0) {
        $err = "Impossible de supprimer : $nbAgents agent(s) sont rattachés à ce domaine.";
    } else {
        $db->prepare("DELETE FROM domaines WHERE id=?")->execute([$id]);
        $msg = 'Domaine supprimé.';
    }
    $action = 'list';
    $id     = 0;
}

// ── Enregistrement (ajout ou modification)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = trim($_POST['nom']     ?? '');
    $couleur = trim($_POST['couleur'] ?? '#1B3A7A');
    $ordre   = (int)($_POST['ordre']  ?? 0);

    if ($nom === '') {
        $err    = 'Le nom du domaine est requis.';
        $action = $id ? 'edit' : 'add';
    } else {
        if ($id) {
            $db->prepare("UPDATE domaines SET nom=?, couleur=?, ordre=? WHERE id=?")
               ->execute([$nom, $couleur, $ordre, $id]);
            $msg = 'Domaine mis à jour.';
        } else {
            $db->prepare("INSERT INTO domaines (nom, couleur, ordre) VALUES (?,?,?)")
               ->execute([$nom, $couleur, $ordre]);
            $msg = 'Domaine créé.';
        }
        $action = 'list';
        $id     = 0;
    }
}

// ── Chargement du domaine à modifier
$domaine = null;
if ($id && $action === 'edit') {
    $stmt = $db->prepare("SELECT * FROM domaines WHERE id=?");
    $stmt->execute([$id]);
    $domaine = $stmt->fetch();
    if (!$domaine) { $id = 0; $action = 'list'; }
}

// ── Liste toujours chargée
$liste = $db->query("
    SELECT d.id, d.nom, d.couleur, d.ordre,
           COUNT(a.id) AS nb_agents
    FROM   domaines d
    LEFT JOIN agents a ON a.pole = d.nom
    GROUP  BY d.id, d.nom, d.couleur, d.ordre
    ORDER  BY d.ordre, d.id
")->fetchAll();
?>

<div class="page-header">
  <h1>🏷️ Domaines</h1>
  <a href="?action=add" class="btn btn-primary btn-sm">+ Nouveau domaine</a>
</div>

<?php if ($msg): ?><div class="alert alert-success py-2"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger  py-2"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- ── Formulaire ajout / modification ── -->
<?php if ($action === 'add' || $action === 'edit'): ?>
<div class="card p-4 mb-4" style="border:2px solid #BDD0FF">
  <h5 class="fw-bold mb-3"><?= $id ? 'Modifier le domaine' : 'Nouveau domaine' ?></h5>
  <form method="post" action="?action=<?= $id ? "edit&id=$id" : 'add' ?>">
    <div class="row g-3 align-items-end">

      <div class="col-md-4">
        <label class="form-label">Nom *</label>
        <input type="text" name="nom" class="form-control"
          value="<?= htmlspecialchars($domaine['nom'] ?? '') ?>"
          required placeholder="ex : Infrastructure">
      </div>

      <div class="col-md-4">
        <label class="form-label">Couleur</label>
        <div class="d-flex align-items-center gap-2">
          <input type="color" id="cp" class="form-control form-control-color"
            value="<?= htmlspecialchars($domaine['couleur'] ?? '#1B3A7A') ?>"
            style="width:46px;height:38px;padding:2px;cursor:pointer"
            oninput="document.getElementById('ch').value=this.value">
          <input type="text" id="ch" name="couleur" class="form-control"
            style="font-family:monospace;font-size:13px" maxlength="7"
            value="<?= htmlspecialchars($domaine['couleur'] ?? '#1B3A7A') ?>"
            oninput="if(/^#[0-9a-fA-F]{6}$/.test(this.value))document.getElementById('cp').value=this.value">
        </div>
        <div class="d-flex gap-1 mt-2 flex-wrap">
          <?php foreach (['#1B3A7A','#00A8D6','#8A6FE8','#F5A020','#5CB85C','#D63030','#6B7BA8','#E91E63','#009688','#FF5722','#795548','#607D8B'] as $c): ?>
          <button type="button"
            onclick="document.getElementById('cp').value='<?= $c ?>';document.getElementById('ch').value='<?= $c ?>';"
            style="width:22px;height:22px;border-radius:50%;background:<?= $c ?>;border:2px solid #fff;cursor:pointer;box-shadow:0 1px 3px rgba(0,0,0,.25)"></button>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="col-md-2">
        <label class="form-label">Ordre</label>
        <input type="number" name="ordre" class="form-control"
          value="<?= (int)($domaine['ordre'] ?? count($liste) + 1) ?>">
      </div>

      <div class="col-md-2 d-flex gap-2 align-items-end">
        <button class="btn btn-primary flex-grow-1">Enregistrer</button>
        <a href="domaines.php" class="btn btn-outline-secondary">✕</a>
      </div>

    </div>
  </form>
</div>
<?php endif; ?>

<!-- ── Tableau des domaines ── -->
<div class="card">
  <?php if (empty($liste)): ?>
  <div class="p-4 text-center text-muted">Aucun domaine. Cliquez sur « + Nouveau domaine » pour commencer.</div>
  <?php else: ?>
  <div class="table-responsive">
    <table class="table mb-0 align-middle">
      <thead>
        <tr>
          <th>Domaine</th>
          <th>Couleur</th>
          <th>Agents rattachés</th>
          <th>Ordre</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($liste as $d):
        $actif = ($id === (int)$d['id'] && $action === 'edit');
      ?>
        <tr style="<?= $actif ? 'background:#EEF3FF' : '' ?>">
          <td>
            <div class="d-flex align-items-center gap-2">
              <span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:<?= htmlspecialchars($d['couleur']) ?>;flex-shrink:0"></span>
              <strong><?= htmlspecialchars($d['nom']) ?></strong>
            </div>
          </td>
          <td><code style="font-size:12px"><?= htmlspecialchars($d['couleur']) ?></code></td>
          <td>
            <?php if ($d['nb_agents'] > 0): ?>
              <span class="badge" style="background:<?= htmlspecialchars($d['couleur']) ?>">
                <?= (int)$d['nb_agents'] ?> agent<?= $d['nb_agents'] > 1 ? 's' : '' ?>
              </span>
            <?php else: ?>
              <span class="text-muted" style="font-size:12px">—</span>
            <?php endif; ?>
          </td>
          <td style="color:#6B7BA8;font-size:13px"><?= (int)$d['ordre'] ?></td>
          <td class="text-end" style="white-space:nowrap">
            <a href="?action=edit&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary me-1">✏️ Modifier</a>
            <?php if ((int)$d['nb_agents'] === 0): ?>
              <a href="?action=delete&id=<?= $d['id'] ?>"
                 class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Supprimer le domaine « <?= htmlspecialchars($d['nom'], ENT_QUOTES) ?> » ?')">
                🗑 Supprimer
              </a>
            <?php else: ?>
              <button class="btn btn-sm btn-outline-secondary" disabled
                title="<?= (int)$d['nb_agents'] ?> agent(s) rattaché(s) — réassignez-les d'abord">
                🗑 Supprimer
              </button>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<p class="text-muted mt-3" style="font-size:12px">
  💡 Pour rattacher des agents à un domaine, allez dans
  <a href="agents.php">Annuaire / Agents</a> et modifiez chaque agent.
</p>

<?php require_once '_footer.php'; ?>
