<?php
$pageTitle = 'Annuaire / Agents';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = '';
$err = '';

$domainesRows = $db->query("SELECT * FROM domaines ORDER BY ordre,id")->fetchAll();
$poles   = array_column($domainesRows, 'nom');
$colors  = array_column($domainesRows, 'couleur');

$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

// ── Delete
if ($action === 'delete' && $id) {
    $db->prepare("DELETE FROM agents WHERE id=?")->execute([$id]);
    $msg = 'Agent supprimé.';
    $action = 'list';
}

// ── Toggle actif
if ($action === 'toggle' && $id) {
    $db->prepare("UPDATE agents SET actif = 1-actif WHERE id=?")->execute([$id]);
    $action = 'list';
}

// ── Save (add/edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        $_POST['nom']        ?? '',
        $_POST['prenom']     ?? '',
        $_POST['role_label'] ?? '',
        $_POST['pole']       ?? 'Autre',
        $_POST['extension']  ?? '',
        $_POST['poste2']     ?? '',
        $_POST['numero_long']?? '',
        $_POST['email']      ?? '',
        $_POST['initiales']  ?? '',
        $_POST['couleur']    ?? '#1B3A7A',
        (int)($_POST['ordre'] ?? 0),
        (int)($_POST['actif'] ?? 1),
    ];
    if (empty(trim($data[0]))) {
        $err = 'Le nom est requis.';
    } else {
        if ($id) {
            $db->prepare("UPDATE agents SET nom=?,prenom=?,role_label=?,pole=?,extension=?,poste2=?,numero_long=?,email=?,initiales=?,couleur=?,ordre=?,actif=? WHERE id=?")->execute([...$data, $id]);
            $msg = 'Agent mis à jour.';
        } else {
            $db->prepare("INSERT INTO agents (nom,prenom,role_label,pole,extension,poste2,numero_long,email,initiales,couleur,ordre,actif) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)")->execute($data);
            $msg = 'Agent ajouté.';
        }
        $action = 'list';
        $id = 0;
    }
}

$agent = null;
if ($id) {
    $stmt = $db->prepare("SELECT * FROM agents WHERE id=?");
    $stmt->execute([$id]);
    $agent = $stmt->fetch();
    if (!$agent) $id = 0;
}
?>

<div class="page-header">
  <h1>👤 Annuaire / Agents</h1>
  <a href="?action=add" class="btn btn-primary">+ Ajouter un agent</a>
</div>

<?php if ($msg): ?><div class="alert alert-success py-2"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if ($action === 'add' || $action === 'edit'): ?>
<div class="card p-4 mb-4">
  <h5 class="fw-bold mb-3"><?= $id ? 'Modifier l\'agent' : 'Nouvel agent' ?></h5>
  <form method="post" action="?action=<?= $id ? "edit&id=$id" : 'add' ?>">
    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label">NOM *</label>
        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($agent['nom'] ?? '') ?>" required placeholder="ex : DUPONT">
      </div>
      <div class="col-md-4">
        <label class="form-label">Prénom</label>
        <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($agent['prenom'] ?? '') ?>" placeholder="ex : Laurent">
      </div>
      <div class="col-md-4">
        <label class="form-label">Rôle / Poste</label>
        <input type="text" name="role_label" class="form-control" value="<?= htmlspecialchars($agent['role_label'] ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Domaine</label>
        <select name="pole" class="form-select">
          <?php foreach ($poles as $p): ?>
          <option value="<?= $p ?>" <?= ($agent['pole'] ?? '') === $p ? 'selected' : '' ?>><?= $p ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Poste (5xxxx)</label>
        <input type="text" name="extension" class="form-control" value="<?= htmlspecialchars($agent['extension'] ?? '') ?>" placeholder="55894">
      </div>
      <div class="col-md-2">
        <label class="form-label">DECT (4xxxx)</label>
        <input type="text" name="poste2" class="form-control" value="<?= htmlspecialchars($agent['poste2'] ?? '') ?>" placeholder="45894">
      </div>
      <div class="col-md-3">
        <label class="form-label">N° long (06/07)</label>
        <input type="text" name="numero_long" class="form-control" value="<?= htmlspecialchars($agent['numero_long'] ?? '') ?>" placeholder="06.65.80.xx.xx">
      </div>
      <div class="col-md-5">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($agent['email'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label">Initiales</label>
        <input type="text" name="initiales" class="form-control" maxlength="5" value="<?= htmlspecialchars($agent['initiales'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label">Couleur</label>
        <select name="couleur" class="form-select">
          <?php foreach ($colors as $c): ?>
          <option value="<?= $c ?>" <?= ($agent['couleur'] ?? '') === $c ? 'selected' : '' ?> style="background:<?= $c ?>;color:#fff"><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Ordre</label>
        <input type="number" name="ordre" class="form-control" value="<?= (int)($agent['ordre'] ?? 0) ?>">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <div class="form-check mb-1">
          <input class="form-check-input" type="checkbox" name="actif" value="1" id="actif" <?= ($agent['actif'] ?? 1) ? 'checked' : '' ?>>
          <label class="form-check-label" for="actif">Actif</label>
        </div>
      </div>
    </div>
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary">Enregistrer</button>
      <a href="agents.php" class="btn btn-outline-secondary">Annuler</a>
    </div>
  </form>
</div>
<?php endif; ?>

<?php if ($action === 'list'):
$agents = $db->query("SELECT * FROM agents ORDER BY nom,prenom")->fetchAll();
$dcMap  = array_column($domainesRows, 'couleur', 'nom');
?>

<!-- Barre de recherche + filtre domaine -->
<div class="d-flex gap-3 mb-3 align-items-center flex-wrap">
  <input id="agentSearch" type="text" class="form-control" placeholder="🔍  Rechercher un agent…" style="max-width:300px"
    oninput="filterAgents()">
  <div class="d-flex gap-2 flex-wrap" id="domaineBtns">
    <button class="btn btn-sm btn-dark active" onclick="filterAgents(this, '')">Tous (<?= count($agents) ?>)</button>
    <?php foreach ($domainesRows as $d): ?>
    <button class="btn btn-sm btn-outline-secondary" data-domaine="<?= htmlspecialchars($d['nom']) ?>"
      onclick="filterAgents(this, '<?= htmlspecialchars($d['nom'], ENT_QUOTES) ?>')"
      style="--dc:<?= htmlspecialchars($d['couleur']) ?>">
      <?= htmlspecialchars($d['nom']) ?>
    </button>
    <?php endforeach; ?>
  </div>
  <span id="agentCount" class="text-muted ms-auto" style="font-size:12px;white-space:nowrap"></span>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table mb-0" id="agentsTable">
      <thead><tr>
        <th style="width:220px">Agent</th>
        <th>Domaine</th>
        <th>Postes</th>
        <th>Email</th>
        <th style="width:50px">Actif</th>
        <th style="width:220px"></th>
      </tr></thead>
      <tbody>
      <?php foreach ($agents as $a):
        $dc = $dcMap[$a['pole']] ?? '#6B7BA8';
      ?>
      <tr class="agent-row" data-domaine="<?= htmlspecialchars($a['pole']) ?>"
          data-search="<?= htmlspecialchars(strtolower($a['nom'].' '.$a['prenom'].' '.($a['role_label']??'').' '.($a['extension']??'').' '.($a['poste2']??'').' '.($a['numero_long']??''))) ?>"
          style="<?= !$a['actif'] ? 'opacity:.45' : '' ?>">
        <td>
          <div class="d-flex align-items-center gap-2">
            <div style="width:34px;height:34px;border-radius:50%;background:<?= $dc ?>;color:#fff;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0"><?= htmlspecialchars($a['initiales']) ?></div>
            <div>
              <div style="font-weight:700;font-size:13px"><?= htmlspecialchars($a['prenom'] ?? '') ?> <?= htmlspecialchars($a['nom']) ?></div>
              <?php if ($a['role_label']): ?>
              <div style="font-size:11px;color:#6B7BA8"><?= htmlspecialchars($a['role_label']) ?></div>
              <?php endif; ?>
            </div>
          </div>
        </td>
        <td>
          <span class="badge" style="background:<?= $dc ?>;font-size:11px"><?= htmlspecialchars($a['pole']) ?></span>
        </td>
        <td style="font-size:12px;line-height:1.7">
          <?php if ($a['extension'])  echo '<span title="Poste fixe">☎ ' . htmlspecialchars($a['extension'])  . '</span><br>'; ?>
          <?php if ($a['poste2'])     echo '<span title="DECT">📟 '    . htmlspecialchars($a['poste2'])     . '</span><br>'; ?>
          <?php if ($a['numero_long'])echo '<span title="Mobile">📱 '  . htmlspecialchars($a['numero_long']).'</span>'; ?>
        </td>
        <td style="font-size:12px">
          <a href="mailto:<?= htmlspecialchars($a['email']) ?>"><?= htmlspecialchars($a['email']) ?></a>
        </td>
        <td>
          <span class="badge bg-<?= $a['actif'] ? 'success' : 'secondary' ?>"><?= $a['actif'] ? 'Oui' : 'Non' ?></span>
        </td>
        <td class="text-end" style="white-space:nowrap">
          <a href="?action=edit&id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary">✏️ Modifier</a>
          <a href="?action=toggle&id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-secondary ms-1"><?= $a['actif'] ? 'Désactiver' : 'Activer' ?></a>
          <a href="?action=delete&id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-danger ms-1"
             onclick="return confirm('Supprimer <?= htmlspecialchars($a['prenom'].' '.$a['nom'], ENT_QUOTES) ?> ?')">🗑</a>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
var currentDomaine = '';
function filterAgents(btn, domaine) {
  currentDomaine = domaine;
  // Boutons domaine
  document.querySelectorAll('#domaineBtns .btn').forEach(b => {
    b.classList.remove('active','btn-dark','text-white');
    b.classList.add('btn-outline-secondary');
    b.style.background = '';
    b.style.color = '';
    b.style.borderColor = '';
  });
  if (btn) {
    btn.classList.remove('btn-outline-secondary');
    btn.classList.add('active');
    var dc = btn.dataset.domaine ? getComputedStyle(btn).getPropertyValue('--dc').trim() : '';
    if (dc) { btn.style.background = dc; btn.style.color='#fff'; btn.style.borderColor=dc; }
    else     { btn.classList.add('btn-dark','text-white'); }
  }
  applyFilter();
}
function applyFilter() {
  var q = document.getElementById('agentSearch').value.toLowerCase().trim();
  var rows = document.querySelectorAll('.agent-row');
  var visible = 0;
  rows.forEach(function(row) {
    var matchD = !currentDomaine || row.dataset.domaine === currentDomaine;
    var matchQ = !q || row.dataset.search.includes(q);
    var show = matchD && matchQ;
    row.style.display = show ? '' : 'none';
    if (show) visible++;
  });
  document.getElementById('agentCount').textContent = visible + ' agent' + (visible > 1 ? 's' : '') + ' affiché' + (visible > 1 ? 's' : '');
}
document.getElementById('agentSearch').addEventListener('input', applyFilter);
applyFilter();
</script>
<?php endif; ?>

<?php require_once '_footer.php'; ?>
