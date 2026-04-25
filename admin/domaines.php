<?php
$pageTitle = 'Domaines';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = '';
$err = '';

$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

// ── Delete
if ($action === 'delete' && $id) {
    $nb = $db->prepare("SELECT COUNT(*) FROM agents WHERE pole=?");
    $nb->execute([$db->query("SELECT nom FROM domaines WHERE id=$id")->fetchColumn()]);
    if ((int)$nb->fetchColumn() > 0) {
        $err = 'Impossible de supprimer : des agents sont rattachés à ce domaine.';
    } else {
        $db->prepare("DELETE FROM domaines WHERE id=?")->execute([$id]);
        $msg = 'Domaine supprimé.';
    }
    $action = 'list';
}

// ── Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = trim($_POST['nom']     ?? '');
    $couleur = trim($_POST['couleur'] ?? '#1B3A7A');
    $ordre   = (int)($_POST['ordre']  ?? 0);
    if ($nom === '') {
        $err = 'Le nom du domaine est requis.';
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
        $id = 0;
    }
}

$domaine = null;
if ($id && ($action === 'edit' || $action === 'add')) {
    $stmt = $db->prepare("SELECT * FROM domaines WHERE id=?");
    $stmt->execute([$id]);
    $domaine = $stmt->fetch();
    if (!$domaine) $id = 0;
}
?>

<div class="page-header">
  <h1>🏷️ Domaines</h1>
  <a href="?action=add" class="btn btn-primary btn-sm">+ Nouveau domaine</a>
</div>

<?php if ($msg): ?><div class="alert alert-success py-2"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if ($action === 'add' || $action === 'edit'): ?>
<div class="card p-4 mb-4">
  <h5 class="fw-bold mb-3"><?= $id ? 'Modifier le domaine' : 'Nouveau domaine' ?></h5>
  <form method="post" action="?action=<?= $id ? "edit&id=$id" : 'add' ?>">
    <div class="row g-3 align-items-end">
      <div class="col-md-5">
        <label class="form-label">Nom du domaine *</label>
        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($domaine['nom'] ?? '') ?>" required placeholder="ex : Infrastructure">
      </div>
      <div class="col-md-3">
        <label class="form-label">Couleur</label>
        <div class="d-flex align-items-center gap-2">
          <input type="color" name="couleur" id="colorPicker" class="form-control form-control-color"
            value="<?= htmlspecialchars($domaine['couleur'] ?? '#1B3A7A') ?>" style="width:48px;height:38px;padding:2px;cursor:pointer">
          <input type="text" id="colorHex" class="form-control" style="font-family:monospace;font-size:13px"
            value="<?= htmlspecialchars($domaine['couleur'] ?? '#1B3A7A') ?>" maxlength="7" placeholder="#1B3A7A">
        </div>
      </div>
      <div class="col-md-2">
        <label class="form-label">Ordre</label>
        <input type="number" name="ordre" class="form-control" value="<?= (int)($domaine['ordre'] ?? 0) ?>">
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button class="btn btn-primary w-100">Enregistrer</button>
      </div>
    </div>
    <!-- Palette de couleurs rapides -->
    <div class="mt-3">
      <div class="form-label mb-2" style="font-size:12px">Palette rapide</div>
      <div class="d-flex gap-2 flex-wrap">
        <?php foreach (['#1B3A7A','#00A8D6','#8A6FE8','#F5A020','#5CB85C','#D63030','#6B7BA8','#E91E63','#009688','#FF5722','#795548','#607D8B'] as $c): ?>
        <button type="button" onclick="document.getElementById('colorPicker').value='<?= $c ?>'; document.getElementById('colorHex').value='<?= $c ?>';"
          style="width:28px;height:28px;border-radius:50%;background:<?= $c ?>;border:2px solid rgba(255,255,255,0.5);cursor:pointer;box-shadow:0 1px 4px rgba(0,0,0,.2)"></button>
        <?php endforeach; ?>
      </div>
    </div>
  </form>
</div>
<script>
const picker = document.getElementById('colorPicker');
const hex    = document.getElementById('colorHex');
picker.addEventListener('input', () => hex.value  = picker.value);
hex.addEventListener('input',   () => { if (/^#[0-9a-fA-F]{6}$/.test(hex.value)) picker.value = hex.value; });
</script>
<?php endif; ?>

<?php if ($action === 'list'): ?>
<?php
$domaines = $db->query("SELECT d.*, (SELECT COUNT(*) FROM agents a WHERE a.pole=d.nom) as nb_agents FROM domaines d ORDER BY d.ordre,d.id")->fetchAll();
?>
<div class="card">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead><tr>
        <th>Domaine</th><th>Couleur</th><th>Agents</th><th>Ordre</th><th></th>
      </tr></thead>
      <tbody>
      <?php foreach ($domaines as $d): ?>
      <tr>
        <td>
          <div class="d-flex align-items-center gap-2">
            <div style="width:12px;height:12px;border-radius:50%;background:<?= htmlspecialchars($d['couleur']) ?>"></div>
            <strong><?= htmlspecialchars($d['nom']) ?></strong>
          </div>
        </td>
        <td><code style="font-size:12px"><?= htmlspecialchars($d['couleur']) ?></code></td>
        <td>
          <?php if ($d['nb_agents'] > 0): ?>
          <span class="badge" style="background:<?= htmlspecialchars($d['couleur']) ?>"><?= (int)$d['nb_agents'] ?> agent<?= $d['nb_agents'] > 1 ? 's' : '' ?></span>
          <?php else: ?>
          <span class="text-muted" style="font-size:12px">—</span>
          <?php endif; ?>
        </td>
        <td><?= (int)$d['ordre'] ?></td>
        <td class="text-end">
          <a href="?action=edit&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary me-1">Modifier</a>
          <?php if ((int)$d['nb_agents'] === 0): ?>
          <a href="?action=delete&id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger"
             onclick="return confirm('Supprimer le domaine « <?= htmlspecialchars($d['nom']) ?> » ?')">Supprimer</a>
          <?php else: ?>
          <button class="btn btn-sm btn-outline-secondary" disabled title="Des agents sont rattachés à ce domaine">Supprimer</button>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<p class="text-muted mt-3" style="font-size:12px">💡 Pour rattacher des agents à un domaine, allez dans <a href="agents.php">Annuaire / Agents</a> et modifiez chaque agent.</p>
<?php endif; ?>

<?php require_once '_footer.php'; ?>
