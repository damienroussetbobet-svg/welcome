<?php
$pageTitle = 'Horaires & planning';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = $err = '';
$colors = ['#5CB85C','#F5A020','#6B7BA8','#00A8D6','#D63030','#1B3A7A'];
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

if ($action==='delete' && $id) { $db->prepare("DELETE FROM horaires WHERE id=?")->execute([$id]); $msg='Horaire supprimé.'; $action='list'; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [trim($_POST['jour']??''), trim($_POST['horaire']??''), trim($_POST['type_horaire']??''), $_POST['couleur']??'#5CB85C', (int)($_POST['ordre']??0)];
    if (empty($data[0])) { $err='Le jour est requis.'; }
    else {
        if ($id) { $db->prepare("UPDATE horaires SET jour=?,horaire=?,type_horaire=?,couleur=?,ordre=? WHERE id=?")->execute([...$data,$id]); $msg='Horaire mis à jour.'; }
        else { $db->prepare("INSERT INTO horaires (jour,horaire,type_horaire,couleur,ordre) VALUES (?,?,?,?,?)")->execute($data); $msg='Horaire ajouté.'; }
        $action='list'; $id=0;
    }
}
$item = ($id && in_array($action,['edit'])) ? $db->query("SELECT * FROM horaires WHERE id=$id")->fetch() : null;
?>
<div class="page-header"><h1>📅 Horaires & planning</h1><a href="?action=add" class="btn btn-primary">+ Ajouter un horaire</a></div>
<?php if ($msg): ?><div class="alert alert-success py-2"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?=htmlspecialchars($err)?></div><?php endif; ?>

<?php if (in_array($action,['add','edit'])): ?>
<div class="card p-4 mb-4">
  <h5 class="fw-bold mb-3"><?=$id?'Modifier':'Nouvel horaire'?></h5>
  <form method="post" action="?action=<?=$id?"edit&id=$id":'add'?>">
    <div class="row g-3">
      <div class="col-md-4"><label class="form-label">Jours *</label><input type="text" name="jour" class="form-control" placeholder="ex: Lundi – Vendredi" value="<?=htmlspecialchars($item['jour']??'')?>" required></div>
      <div class="col-md-3"><label class="form-label">Horaires</label><input type="text" name="horaire" class="form-control" placeholder="7h30 – 18h30" value="<?=htmlspecialchars($item['horaire']??'')?>"></div>
      <div class="col-md-3"><label class="form-label">Type</label><input type="text" name="type_horaire" class="form-control" placeholder="Heures ouvrées" value="<?=htmlspecialchars($item['type_horaire']??'')?>"></div>
      <div class="col-md-2"><label class="form-label">Couleur</label>
        <select name="couleur" class="form-select"><?php foreach($colors as $c): ?><option value="<?=$c?>" <?=($item['couleur']??'')===$c?'selected':''?> style="background:<?=$c?>;color:#fff"><?=$c?></option><?php endforeach;?></select>
      </div>
      <div class="col-md-2"><label class="form-label">Ordre</label><input type="number" name="ordre" class="form-control" value="<?=(int)($item['ordre']??0)?>"></div>
    </div>
    <div class="mt-3 d-flex gap-2"><button class="btn btn-primary">Enregistrer</button><a href="planning.php" class="btn btn-outline-secondary">Annuler</a></div>
  </form>
</div>
<?php endif; ?>

<?php if ($action==='list'): ?>
<div class="card"><div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Jours</th><th>Horaires</th><th>Type</th><th></th></tr></thead>
<tbody>
<?php foreach($db->query("SELECT * FROM horaires ORDER BY ordre,id")->fetchAll() as $h): ?>
<tr>
  <td><div class="d-flex align-items-center gap-2"><div style="width:10px;height:10px;border-radius:50%;background:<?=htmlspecialchars($h['couleur'])?>"></div><strong><?=htmlspecialchars($h['jour'])?></strong></div></td>
  <td style="font-weight:700;color:<?=htmlspecialchars($h['couleur'])?>"><?=htmlspecialchars($h['horaire'])?></td>
  <td><?=htmlspecialchars($h['type_horaire'])?></td>
  <td class="text-end"><a href="?action=edit&id=<?=$h['id']?>" class="btn btn-sm btn-outline-primary me-1">Modifier</a><a href="?action=delete&id=<?=$h['id']?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>
<?php require_once '_footer.php'; ?>
