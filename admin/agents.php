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

<?php if ($action === 'list'): ?>
<div class="card">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead><tr>
        <th>Nom / Prénom</th><th>Rôle</th><th>Domaine</th><th>Poste</th><th>DECT</th><th>N° long</th><th>Email</th><th>Actif</th><th></th>
      </tr></thead>
      <tbody>
      <?php
      $agents = $db->query("SELECT * FROM agents ORDER BY pole,ordre,id")->fetchAll();
      $lastPole = null;
      foreach ($agents as $a):
        if ($a['pole'] !== $lastPole):
          $lastPole = $a['pole'];
      ?>
        <tr style="background:#F0F4FF"><td colspan="9" class="py-1 px-3 fw-bold" style="font-size:11px;color:#6B7BA8;text-transform:uppercase;letter-spacing:.05em"><?= htmlspecialchars($a['pole']) ?></td></tr>
      <?php endif; ?>
      <tr style="<?= !$a['actif'] ? 'opacity:.45' : '' ?>">
        <td>
          <div class="d-flex align-items-center gap-2">
            <div style="width:32px;height:32px;border-radius:50%;background:<?= htmlspecialchars($a['couleur']) ?>;color:#fff;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0"><?= htmlspecialchars($a['initiales']) ?></div>
            <div><strong><?= htmlspecialchars($a['nom']) ?></strong><br><span style="font-size:12px;color:#6B7BA8"><?= htmlspecialchars($a['prenom'] ?? '') ?></span></div>
          </div>
        </td>
        <td><?= htmlspecialchars($a['role_label']) ?></td>
        <td><span class="badge" style="background:<?= htmlspecialchars($a['couleur']) ?>"><?= htmlspecialchars($a['pole']) ?></span></td>
        <td style="font-size:12px"><?= htmlspecialchars($a['extension'] ?? '') ?></td>
        <td style="font-size:12px"><?= htmlspecialchars($a['poste2'] ?? '') ?></td>
        <td style="font-size:12px"><?= htmlspecialchars($a['numero_long'] ?? '') ?></td>
        <td><a href="mailto:<?= htmlspecialchars($a['email']) ?>" style="font-size:12px"><?= htmlspecialchars($a['email']) ?></a></td>
        <td><span class="badge bg-<?= $a['actif'] ? 'success' : 'secondary' ?>"><?= $a['actif'] ? 'Oui' : 'Non' ?></span></td>
        <td class="text-end">
          <a href="?action=edit&id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary me-1">Modifier</a>
          <a href="?action=toggle&id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-secondary me-1"><?= $a['actif'] ? 'Désactiver' : 'Activer' ?></a>
          <a href="?action=delete&id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer cet agent ?')">Supprimer</a>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<?php require_once '_footer.php'; ?>
