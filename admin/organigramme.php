<?php
$pageTitle = 'Organigramme';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = $err = '';
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

// ── Suppression
if ($action === 'delete' && $id) {
    $node = $db->query("SELECT parent_id FROM org_nodes WHERE id=$id")->fetch();
    $db->prepare("UPDATE org_nodes SET parent_id=? WHERE parent_id=?")->execute([$node['parent_id'], $id]);
    $db->prepare("DELETE FROM org_nodes WHERE id=?")->execute([$id]);
    $msg = 'Nœud supprimé.';
    $action = 'list'; $id = 0;
}

// ── Enregistrement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom        = trim($_POST['nom'] ?? '');
    $role_label = trim($_POST['role_label'] ?? '');
    $initiales  = strtoupper(trim($_POST['initiales'] ?? ''));
    $couleur    = $_POST['couleur'] ?? '#1B3A7A';
    $parent_id  = (int)($_POST['parent_id'] ?? 0) ?: null;
    $ordre      = (int)($_POST['ordre'] ?? 0);
    $agent_id   = (int)($_POST['agent_id'] ?? 0) ?: null;

    if (empty($nom)) {
        $err = 'Le nom est requis.';
    } else {
        if ($id) {
            $db->prepare("UPDATE org_nodes SET parent_id=?,agent_id=?,nom=?,role_label=?,initiales=?,couleur=?,ordre=? WHERE id=?")
               ->execute([$parent_id, $agent_id, $nom, $role_label, $initiales, $couleur, $ordre, $id]);
            $msg = 'Nœud mis à jour.';
        } else {
            $db->prepare("INSERT INTO org_nodes (parent_id,agent_id,nom,role_label,initiales,couleur,ordre) VALUES (?,?,?,?,?,?,?)")
               ->execute([$parent_id, $agent_id, $nom, $role_label, $initiales, $couleur, $ordre]);
            $msg = 'Nœud ajouté.';
        }
        $action = 'list'; $id = 0;
    }
}

// ── Chargement nœud en édition
$item = null;
if ($id && $action === 'edit') {
    $item = $db->query("SELECT * FROM org_nodes WHERE id=$id")->fetch();
    if (!$item) { $id = 0; $action = 'list'; }
}

// ── Tous les nœuds avec agent joint
$allNodes = $db->query("
    SELECT n.*,
           a.nom as a_nom, a.prenom as a_prenom,
           a.extension, a.poste2 as a_poste2, a.numero_long, a.email as a_email
    FROM   org_nodes n
    LEFT   JOIN agents a ON a.id = n.agent_id
    ORDER  BY n.ordre, n.id
")->fetchAll();
$nodeMap = array_column($allNodes, null, 'id');

// ── Agents actifs pour le sélecteur
$agents  = $db->query("SELECT id, nom, prenom, role_label, initiales, couleur FROM agents WHERE actif=1 ORDER BY nom, prenom")->fetchAll();

// ── Domaines pour la palette couleur
$domaines = $db->query("SELECT nom, couleur FROM domaines ORDER BY ordre, id")->fetchAll();

// ── Arbre indenté (pour <select> et liste)
function indentTree(array $nodes, ?int $parentId, int $depth, int $excludeId = 0): array {
    $result = [];
    foreach ($nodes as $n) {
        $nPid = $n['parent_id'] === null ? null : (int)$n['parent_id'];
        if ($nPid === $parentId && (int)$n['id'] !== $excludeId) {
            $n['depth'] = $depth;
            $result[] = $n;
            $result = array_merge($result, indentTree($nodes, (int)$n['id'], $depth + 1, $excludeId));
        }
    }
    return $result;
}
$tree = indentTree($allNodes, null, 0, $id);

// Pre-fill parent if coming from "+ Sous-poste"
$preParentId = (int)($_GET['parent_id'] ?? 0);
?>

<div class="page-header">
  <h1>🏗️ Organigramme</h1>
  <a href="?action=add" class="btn btn-primary">+ Ajouter un poste</a>
</div>
<div class="alert alert-info py-2 small mb-3">
  Chaque nœud peut être lié à un agent de l'annuaire. Les informations de contact apparaîtront automatiquement dans le livret. Un même agent peut figurer plusieurs fois (temps partiel, intérim).
</div>

<?php if ($msg): ?><div class="alert alert-success py-2"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger  py-2"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if (in_array($action, ['add', 'edit'])): ?>
<div class="card p-4 mb-4" style="border:2px solid #BDD0FF">
  <h5 class="fw-bold mb-3"><?= $id ? 'Modifier le poste' : 'Nouveau poste' ?></h5>
  <form method="post" action="?action=<?= $id ? "edit&id=$id" : 'add' ?>">
    <div class="row g-3">

      <!-- Agent de l'annuaire -->
      <div class="col-12">
        <label class="form-label">Agent de l'annuaire <span class="text-muted fw-normal">(facultatif — remplit automatiquement les champs ci-dessous)</span></label>
        <div class="d-flex gap-2 align-items-center">
          <input type="text" id="agentSearch" class="form-control" placeholder="🔍  Rechercher un agent…" autocomplete="off" style="max-width:320px">
          <select name="agent_id" id="agentSelect" class="form-select" style="max-width:360px">
            <option value="0">— Aucun agent lié —</option>
            <?php foreach ($agents as $a): ?>
            <option value="<?= $a['id'] ?>"
              data-nom="<?= htmlspecialchars($a['nom']) ?>"
              data-prenom="<?= htmlspecialchars($a['prenom'] ?? '') ?>"
              data-role="<?= htmlspecialchars($a['role_label'] ?? '') ?>"
              data-initiales="<?= htmlspecialchars($a['initiales'] ?? '') ?>"
              data-couleur="<?= htmlspecialchars($a['couleur'] ?? '#1B3A7A') ?>"
              <?= ((int)($item['agent_id'] ?? 0) === (int)$a['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($a['nom']) ?> <?= htmlspecialchars($a['prenom'] ?? '') ?>
              <?= $a['role_label'] ? ' — ' . htmlspecialchars($a['role_label']) : '' ?>
            </option>
            <?php endforeach; ?>
          </select>
          <button type="button" id="clearAgent" class="btn btn-outline-secondary btn-sm">✕ Délier</button>
        </div>
      </div>

      <!-- Champ Rôle / Poste (affiché en premier dans le nœud) -->
      <div class="col-md-4">
        <label class="form-label">Rôle / Poste affiché sur le nœud</label>
        <input type="text" name="role_label" id="f_role" class="form-control"
          value="<?= htmlspecialchars($item['role_label'] ?? '') ?>"
          placeholder="ex : Chef de pôle Infrastructure">
      </div>

      <!-- Nom -->
      <div class="col-md-4">
        <label class="form-label">Nom complet *</label>
        <input type="text" name="nom" id="f_nom" class="form-control"
          value="<?= htmlspecialchars($item['nom'] ?? '') ?>" required
          placeholder="ex : Marie DUPONT">
      </div>

      <!-- Initiales -->
      <div class="col-md-2">
        <label class="form-label">Initiales</label>
        <input type="text" name="initiales" id="f_initiales" class="form-control" maxlength="5"
          value="<?= htmlspecialchars($item['initiales'] ?? '') ?>"
          placeholder="MD">
      </div>

      <!-- Couleur -->
      <div class="col-md-2">
        <label class="form-label">Couleur</label>
        <div class="d-flex align-items-center gap-2">
          <input type="color" id="cp" class="form-control form-control-color"
            value="<?= htmlspecialchars($item['couleur'] ?? '#1B3A7A') ?>"
            style="width:46px;height:38px;padding:2px;cursor:pointer"
            oninput="document.getElementById('ch').value=this.value">
          <input type="text" id="ch" name="couleur" class="form-control"
            style="font-family:monospace;font-size:13px" maxlength="7"
            value="<?= htmlspecialchars($item['couleur'] ?? '#1B3A7A') ?>"
            oninput="if(/^#[0-9a-fA-F]{6}$/.test(this.value))document.getElementById('cp').value=this.value">
        </div>
        <div class="d-flex gap-1 mt-2 flex-wrap">
          <?php foreach ($domaines as $dom): ?>
          <button type="button" title="<?= htmlspecialchars($dom['nom']) ?>"
            onclick="document.getElementById('cp').value='<?= $dom['couleur'] ?>';document.getElementById('ch').value='<?= $dom['couleur'] ?>';"
            style="width:22px;height:22px;border-radius:50%;background:<?= $dom['couleur'] ?>;border:2px solid #fff;cursor:pointer;box-shadow:0 1px 3px rgba(0,0,0,.25)"></button>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Supérieur hiérarchique -->
      <div class="col-md-5">
        <label class="form-label">Supérieur hiérarchique</label>
        <select name="parent_id" class="form-select">
          <option value="0">— Aucun (racine) —</option>
          <?php foreach ($tree as $n): ?>
          <option value="<?= $n['id'] ?>"
            <?= (int)($item['parent_id'] ?? $preParentId) === (int)$n['id'] ? 'selected' : '' ?>>
            <?= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $n['depth']) ?>📌
            <?= htmlspecialchars($n['nom']) ?>
            <?= $n['role_label'] ? ' — ' . htmlspecialchars($n['role_label']) : '' ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Ordre -->
      <div class="col-md-2">
        <label class="form-label">Ordre</label>
        <input type="number" name="ordre" class="form-control"
          value="<?= (int)($item['ordre'] ?? 0) ?>">
      </div>

    </div>
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary">Enregistrer</button>
      <a href="organigramme.php" class="btn btn-outline-secondary">Annuler</a>
    </div>
  </form>
</div>

<script>
(function() {
  var sel   = document.getElementById('agentSelect');
  var srch  = document.getElementById('agentSearch');
  var clear = document.getElementById('clearAgent');

  // Filter select options by search
  var allOpts = Array.from(sel.options).slice(1); // skip "— Aucun —"
  srch.addEventListener('input', function() {
    var q = this.value.toLowerCase();
    allOpts.forEach(function(opt) {
      opt.style.display = (!q || opt.text.toLowerCase().includes(q)) ? '' : 'none';
    });
  });

  // Auto-fill fields when agent selected
  sel.addEventListener('change', function() {
    var opt = sel.options[sel.selectedIndex];
    if (sel.value === '0') return;
    var nom      = opt.dataset.nom || '';
    var prenom   = opt.dataset.prenom || '';
    var fullName = prenom ? (prenom + ' ' + nom) : nom;
    document.getElementById('f_nom').value      = fullName;
    document.getElementById('f_role').value     = opt.dataset.role || '';
    document.getElementById('f_initiales').value= opt.dataset.initiales || '';
    var c = opt.dataset.couleur || '#1B3A7A';
    document.getElementById('cp').value = c;
    document.getElementById('ch').value = c;
  });

  // Clear agent link
  clear.addEventListener('click', function() {
    sel.value = '0';
    srch.value = '';
    allOpts.forEach(function(opt) { opt.style.display = ''; });
  });
})();
</script>
<?php endif; ?>

<!-- ── Liste hiérarchique ── -->
<?php if ($action === 'list'): ?>
<div class="card">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead><tr>
        <th>Hiérarchie</th>
        <th>Rôle</th>
        <th>Agent lié</th>
        <th>Contact</th>
        <th class="text-end"></th>
      </tr></thead>
      <tbody>
      <?php foreach (indentTree($allNodes, null, 0) as $n):
        $dc = $nodeMap[$n['id']]['couleur'] ?? '#1B3A7A';
      ?>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2" style="padding-left:<?= $n['depth'] * 20 ?>px">
              <?php if ($n['depth'] > 0): ?><span style="color:#C5CFEE;font-size:16px">└</span><?php endif; ?>
              <div style="width:28px;height:28px;border-radius:50%;background:<?= htmlspecialchars($dc) ?>;color:#fff;font-size:10px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <?= htmlspecialchars($n['initiales'] ?? mb_substr($n['nom'], 0, 2)) ?>
              </div>
              <strong style="font-size:13px"><?= htmlspecialchars($n['nom']) ?></strong>
            </div>
          </td>
          <td style="color:#6B7BA8;font-size:12px"><?= htmlspecialchars($n['role_label'] ?? '') ?></td>
          <td style="font-size:12px">
            <?php if ($n['agent_id']): ?>
              <span class="badge" style="background:<?= htmlspecialchars($dc) ?>;font-size:11px">
                🔗 <?= htmlspecialchars(($n['a_prenom'] ? $n['a_prenom'].' ' : '') . $n['a_nom']) ?>
              </span>
            <?php else: ?>
              <span class="text-muted" style="font-size:11px">—</span>
            <?php endif; ?>
          </td>
          <td style="font-size:11px;color:#6B7BA8;line-height:1.8">
            <?php if ($n['extension']):  echo '☎ ' . htmlspecialchars($n['extension'])  . '<br>'; endif; ?>
            <?php if ($n['a_poste2']):   echo '📟 ' . htmlspecialchars($n['a_poste2'])   . '<br>'; endif; ?>
            <?php if ($n['numero_long']): echo '📱 ' . htmlspecialchars($n['numero_long']) . '<br>'; endif; ?>
            <?php if ($n['a_email']):    echo '✉ '  . htmlspecialchars($n['a_email']);    endif; ?>
          </td>
          <td class="text-end" style="white-space:nowrap">
            <a href="?action=add&parent_id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-secondary me-1">+ Sous-poste</a>
            <a href="?action=edit&id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-primary me-1">✏️ Modifier</a>
            <a href="?action=delete&id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Supprimer ce poste ? Ses enfants remonteront au niveau supérieur.')">🗑</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>

<?php require_once '_footer.php'; ?>
