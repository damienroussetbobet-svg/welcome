<?php
$pageTitle = 'Contacts utiles';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = $err = '';

$colors = ['#1B3A7A','#00A8D6','#8A6FE8','#F5A020','#5CB85C','#D63030','#6B7BA8'];
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id) { $db->prepare("DELETE FROM contacts WHERE id=?")->execute([$id]); $msg='Contact supprimé.'; $action='list'; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [trim($_POST['nom']??''), trim($_POST['role_label']??''), trim($_POST['extension']??''), trim($_POST['email']??''), $_POST['couleur']??'#1B3A7A', (int)($_POST['ordre']??0), (int)($_POST['actif']??1)];
    if (empty($data[0])) { $err='Le nom est requis.'; } else {
        if ($id) { $db->prepare("UPDATE contacts SET nom=?,role_label=?,extension=?,email=?,couleur=?,ordre=?,actif=? WHERE id=?")->execute([...$data,$id]); $msg='Contact mis à jour.'; }
        else { $db->prepare("INSERT INTO contacts (nom,role_label,extension,email,couleur,ordre,actif) VALUES (?,?,?,?,?,?,?)")->execute($data); $msg='Contact ajouté.'; }
        $action='list'; $id=0;
    }
}
$contact = ($id && in_array($action,['edit'])) ? $db->query("SELECT * FROM contacts WHERE id=$id")->fetch() : null;
?>
<div class="page-header"><h1>📞 Contacts utiles</h1><a href="?action=add" class="btn btn-primary">+ Ajouter</a></div>
<?php if ($msg): ?><div class="alert alert-success py-2"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<?php if (in_array($action,['add','edit'])): ?>
<div class="card p-4 mb-4">
  <h5 class="fw-bold mb-3"><?= $id?'Modifier':'Nouveau contact' ?></h5>
  <form method="post" action="?action=<?= $id?"edit&id=$id":'add' ?>">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Nom *</label><input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($contact['nom']??'') ?>" required></div>
      <div class="col-md-6"><label class="form-label">Rôle</label><input type="text" name="role_label" class="form-control" value="<?= htmlspecialchars($contact['role_label']??'') ?>"></div>
      <div class="col-md-3"><label class="form-label">Extension</label><input type="text" name="extension" class="form-control" value="<?= htmlspecialchars($contact['extension']??'') ?>"></div>
      <div class="col-md-5"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($contact['email']??'') ?>"></div>
      <div class="col-md-2"><label class="form-label">Couleur</label>
        <select name="couleur" class="form-select"><?php foreach($colors as $c): ?><option value="<?=$c?>" <?=($contact['couleur']??'')===$c?'selected':''?> style="background:<?=$c?>;color:#fff"><?=$c?></option><?php endforeach;?></select>
      </div>
      <div class="col-md-1"><label class="form-label">Ordre</label><input type="number" name="ordre" class="form-control" value="<?=(int)($contact['ordre']??0)?>"></div>
      <div class="col-md-1 d-flex align-items-end"><div class="form-check mb-1"><input class="form-check-input" type="checkbox" name="actif" value="1" id="actif" <?=($contact['actif']??1)?'checked':''?>><label class="form-check-label" for="actif">Actif</label></div></div>
    </div>
    <div class="mt-3 d-flex gap-2"><button class="btn btn-primary">Enregistrer</button><a href="contacts.php" class="btn btn-outline-secondary">Annuler</a></div>
  </form>
</div>
<?php endif; ?>

<?php if ($action==='list'): ?>
<div class="card"><div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Nom</th><th>Rôle</th><th>Extension</th><th>Email</th><th>Actif</th><th></th></tr></thead>
<tbody>
<?php foreach($db->query("SELECT * FROM contacts ORDER BY ordre,id")->fetchAll() as $c): ?>
<tr style="<?=!$c['actif']?'opacity:.4':''?>">
  <td><div class="d-flex align-items-center gap-2"><div style="width:32px;height:32px;border-radius:50%;background:<?=htmlspecialchars($c['couleur'])?>;color:#fff;font-size:14px;font-weight:800;display:flex;align-items:center;justify-content:center"><?=htmlspecialchars(mb_substr($c['nom'],0,1))?></div><strong><?=htmlspecialchars($c['nom'])?></strong></div></td>
  <td><?=htmlspecialchars($c['role_label'])?></td>
  <td>ext. <?=htmlspecialchars($c['extension'])?></td>
  <td><a href="mailto:<?=htmlspecialchars($c['email'])?>" style="font-size:12px"><?=htmlspecialchars($c['email'])?></a></td>
  <td><span class="badge bg-<?=$c['actif']?'success':'secondary'?>"><?=$c['actif']?'Oui':'Non'?></span></td>
  <td class="text-end"><a href="?action=edit&id=<?=$c['id']?>" class="btn btn-sm btn-outline-primary me-1">Modifier</a><a href="?action=delete&id=<?=$c['id']?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>
<?php require_once '_footer.php'; ?>
