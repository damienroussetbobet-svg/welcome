<?php
$pageTitle = 'Organigramme';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = $err = '';
$colors = ['#1B3A7A','#00A8D6','#8A6FE8','#F5A020','#5CB85C','#D63030','#6B7BA8'];
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

if ($action==='delete' && $id) {
    // Move children to parent of deleted node
    $node = $db->query("SELECT parent_id FROM org_nodes WHERE id=$id")->fetch();
    $db->prepare("UPDATE org_nodes SET parent_id=? WHERE parent_id=?")->execute([$node['parent_id'],$id]);
    $db->prepare("DELETE FROM org_nodes WHERE id=?")->execute([$id]);
    $msg='Nœud supprimé.'; $action='list';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = trim($_POST['nom']??'');
    $role_label= trim($_POST['role_label']??'');
    $initiales = strtoupper(trim($_POST['initiales']??''));
    $couleur   = $_POST['couleur']??'#1B3A7A';
    $parent_id = (int)($_POST['parent_id']??0) ?: null;
    $ordre     = (int)($_POST['ordre']??0);

    if (empty($nom)) { $err='Le nom est requis.'; }
    else {
        if ($id) {
            $db->prepare("UPDATE org_nodes SET parent_id=?,nom=?,role_label=?,initiales=?,couleur=?,ordre=? WHERE id=?")->execute([$parent_id,$nom,$role_label,$initiales,$couleur,$ordre,$id]);
            $msg='Nœud mis à jour.';
        } else {
            $db->prepare("INSERT INTO org_nodes (parent_id,nom,role_label,initiales,couleur,ordre) VALUES (?,?,?,?,?,?)")->execute([$parent_id,$nom,$role_label,$initiales,$couleur,$ordre]);
            $msg='Nœud ajouté.';
        }
        $action='list'; $id=0;
    }
}
$item    = ($id && in_array($action,['edit'])) ? $db->query("SELECT * FROM org_nodes WHERE id=$id")->fetch() : null;
$allNodes = $db->query("SELECT id,nom,role_label,initiales,couleur,parent_id FROM org_nodes ORDER BY ordre,id")->fetchAll();
$nodeMap  = array_column($allNodes, null, 'id');

// Build indented display
function indentTree(array $nodes, ?int $parentId, int $depth, int $excludeId = 0): array {
    $result = [];
    foreach ($nodes as $n) {
        $nPid = $n['parent_id'] === null ? null : (int)$n['parent_id'];
        if ($nPid === $parentId && (int)$n['id'] !== $excludeId) {
            $n['depth'] = $depth;
            $result[] = $n;
            $result = array_merge($result, indentTree($nodes, (int)$n['id'], $depth+1, $excludeId));
        }
    }
    return $result;
}
$tree = indentTree($allNodes, null, 0, $id);
?>
<div class="page-header"><h1>🏗️ Organigramme</h1><a href="?action=add" class="btn btn-primary">+ Ajouter un poste</a></div>
<div class="alert alert-info py-2 small">L'organigramme est affiché en arbre interactif sur le livret. Modifiez les nœuds ici pour mettre à jour la hiérarchie.</div>
<?php if ($msg): ?><div class="alert alert-success py-2"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?=htmlspecialchars($err)?></div><?php endif; ?>

<?php if (in_array($action,['add','edit'])): ?>
<div class="card p-4 mb-4">
  <h5 class="fw-bold mb-3"><?=$id?'Modifier le poste':'Nouveau poste'?></h5>
  <form method="post" action="?action=<?=$id?"edit&id=$id":'add'?>">
    <div class="row g-3">
      <div class="col-md-5"><label class="form-label">Nom *</label><input type="text" name="nom" class="form-control" value="<?=htmlspecialchars($item['nom']??'')?>" required></div>
      <div class="col-md-5"><label class="form-label">Rôle / Poste</label><input type="text" name="role_label" class="form-control" value="<?=htmlspecialchars($item['role_label']??'')?>"></div>
      <div class="col-md-2"><label class="form-label">Initiales (2-3)</label><input type="text" name="initiales" class="form-control" maxlength="5" value="<?=htmlspecialchars($item['initiales']??'')?>"></div>
      <div class="col-md-4">
        <label class="form-label">Supérieur hiérarchique</label>
        <select name="parent_id" class="form-select">
          <option value="0">— Aucun (racine) —</option>
          <?php foreach($tree as $n): ?>
          <option value="<?=$n['id']?>" <?=(int)($item['parent_id']??0)===$n['id']?'selected':''?>><?=str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$n['depth']).'📌 '.htmlspecialchars($n['nom']).' – '.htmlspecialchars($n['role_label'])?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3"><label class="form-label">Couleur (pôle)</label>
        <select name="couleur" class="form-select"><?php foreach($colors as $c): ?><option value="<?=$c?>" <?=($item['couleur']??'')===$c?'selected':''?> style="background:<?=$c?>;color:#fff"><?=$c?></option><?php endforeach;?></select>
      </div>
      <div class="col-md-2"><label class="form-label">Ordre</label><input type="number" name="ordre" class="form-control" value="<?=(int)($item['ordre']??0)?>"></div>
    </div>
    <div class="mt-3 d-flex gap-2"><button class="btn btn-primary">Enregistrer</button><a href="organigramme.php" class="btn btn-outline-secondary">Annuler</a></div>
  </form>
</div>
<?php endif; ?>

<?php if ($action==='list'): ?>
<div class="card"><div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Hiérarchie</th><th>Rôle</th><th>Init.</th><th></th></tr></thead>
<tbody>
<?php foreach(indentTree($allNodes, null, 0) as $n): ?>
<tr>
  <td>
    <div class="d-flex align-items-center gap-2" style="padding-left:<?=($n['depth']*20)?>px">
      <?php if ($n['depth'] > 0): ?><span style="color:#C5CFEE;font-size:16px">└</span><?php endif; ?>
      <div style="width:28px;height:28px;border-radius:50%;background:<?=htmlspecialchars($nodeMap[$n['id']]['couleur']??'#1B3A7A')?>;color:#fff;font-size:10px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0"><?=htmlspecialchars($n['initiales']??mb_substr($n['nom'],0,2))?></div>
      <strong style="font-size:13px"><?=htmlspecialchars($n['nom'])?></strong>
    </div>
  </td>
  <td style="color:#6B7BA8;font-size:12px"><?=htmlspecialchars($n['role_label'])?></td>
  <td><code style="font-size:11px"><?=htmlspecialchars($n['initiales']??'')?></code></td>
  <td class="text-end">
    <a href="?action=add&parent_id=<?=$n['id']?>" class="btn btn-sm btn-outline-secondary me-1">+ Sous-poste</a>
    <a href="?action=edit&id=<?=$n['id']?>" class="btn btn-sm btn-outline-primary me-1">Modifier</a>
    <a href="?action=delete&id=<?=$n['id']?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce poste ? Ses enfants remonteront au niveau supérieur.')">Supprimer</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>
<?php require_once '_footer.php'; ?>
