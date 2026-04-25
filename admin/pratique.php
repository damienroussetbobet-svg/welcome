<?php
$pageTitle = 'Infos pratiques';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = $err = '';
$colors = ['#00A8D6','#8A6FE8','#F5A020','#5CB85C','#1B3A7A','#D63030'];
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

if ($action==='delete' && $id) { $db->prepare("DELETE FROM infos_pratiques WHERE id=?")->execute([$id]); $msg='Info supprimée.'; $action='list'; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [trim($_POST['titre']??''), trim($_POST['contenu']??''), trim($_POST['icone']??'info'), $_POST['couleur']??'#00A8D6', (int)($_POST['ordre']??0)];
    if (empty($data[0])) { $err='Le titre est requis.'; }
    else {
        if ($id) { $db->prepare("UPDATE infos_pratiques SET titre=?,contenu=?,icone=?,couleur=?,ordre=? WHERE id=?")->execute([...$data,$id]); $msg='Info mise à jour.'; }
        else { $db->prepare("INSERT INTO infos_pratiques (titre,contenu,icone,couleur,ordre) VALUES (?,?,?,?,?)")->execute($data); $msg='Info ajoutée.'; }
        $action='list'; $id=0;
    }
}
$item = ($id && in_array($action,['edit'])) ? $db->query("SELECT * FROM infos_pratiques WHERE id=$id")->fetch() : null;
?>
<div class="page-header"><h1>ℹ️ Infos pratiques</h1><a href="?action=add" class="btn btn-primary">+ Ajouter</a></div>
<?php if ($msg): ?><div class="alert alert-success py-2"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?=htmlspecialchars($err)?></div><?php endif; ?>

<?php if (in_array($action,['add','edit'])): ?>
<div class="card p-4 mb-4">
  <h5 class="fw-bold mb-3"><?=$id?'Modifier':'Nouvelle info pratique'?></h5>
  <form method="post" action="?action=<?=$id?"edit&id=$id":'add'?>">
    <div class="row g-3">
      <div class="col-md-5"><label class="form-label">Titre *</label><input type="text" name="titre" class="form-control" value="<?=htmlspecialchars($item['titre']??'')?>" required></div>
      <div class="col-md-3"><label class="form-label">Icône Lucide</label><input type="text" name="icone" class="form-control" placeholder="badge, car, utensils…" value="<?=htmlspecialchars($item['icone']??'info')?>"></div>
      <div class="col-md-2"><label class="form-label">Couleur</label>
        <select name="couleur" class="form-select"><?php foreach($colors as $c): ?><option value="<?=$c?>" <?=($item['couleur']??'')===$c?'selected':''?> style="background:<?=$c?>;color:#fff"><?=$c?></option><?php endforeach;?></select>
      </div>
      <div class="col-md-2"><label class="form-label">Ordre</label><input type="number" name="ordre" class="form-control" value="<?=(int)($item['ordre']??0)?>"></div>
      <div class="col-12"><label class="form-label">Contenu</label><textarea name="contenu" class="form-control" rows="3"><?=htmlspecialchars($item['contenu']??'')?></textarea></div>
    </div>
    <div class="mt-3 d-flex gap-2"><button class="btn btn-primary">Enregistrer</button><a href="pratique.php" class="btn btn-outline-secondary">Annuler</a></div>
  </form>
</div>
<?php endif; ?>

<?php if ($action==='list'): ?>
<div class="card"><div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Titre</th><th>Icône</th><th>Contenu (extrait)</th><th></th></tr></thead>
<tbody>
<?php foreach($db->query("SELECT * FROM infos_pratiques ORDER BY ordre,id")->fetchAll() as $inf): ?>
<tr>
  <td><div class="d-flex align-items-center gap-2"><div style="width:10px;height:10px;border-radius:3px;background:<?=htmlspecialchars($inf['couleur'])?>"></div><strong><?=htmlspecialchars($inf['titre'])?></strong></div></td>
  <td><code><?=htmlspecialchars($inf['icone'])?></code></td>
  <td style="font-size:12px;color:#6B7BA8"><?=htmlspecialchars(mb_substr($inf['contenu']??'',0,70))?>…</td>
  <td class="text-end"><a href="?action=edit&id=<?=$inf['id']?>" class="btn btn-sm btn-outline-primary me-1">Modifier</a><a href="?action=delete&id=<?=$inf['id']?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>
<?php require_once '_footer.php'; ?>
