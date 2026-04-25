<?php
$pageTitle = 'Procédures';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = $err = '';
$colors = ['#00A8D6','#D63030','#8A6FE8','#F5A020','#5CB85C','#1B3A7A'];
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

if ($action==='delete' && $id) {
    $db->prepare("DELETE FROM procedures WHERE id=?")->execute([$id]);
    $msg='Procédure supprimée.'; $action='list';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre  = trim($_POST['titre']??'');
    $tag    = trim($_POST['tag']??'');
    $couleur= $_POST['couleur']??'#00A8D6';
    $ordre  = (int)($_POST['ordre']??0);
    $actif  = (int)($_POST['actif']??1);
    $etapes = array_filter(array_map('trim', explode("\n", $_POST['etapes']??'')));

    if (empty($titre)) { $err='Le titre est requis.'; }
    else {
        if ($id) {
            $db->prepare("UPDATE procedures SET titre=?,tag=?,couleur=?,ordre=?,actif=? WHERE id=?")->execute([$titre,$tag,$couleur,$ordre,$actif,$id]);
            $db->prepare("DELETE FROM procedure_etapes WHERE procedure_id=?")->execute([$id]);
        } else {
            $db->prepare("INSERT INTO procedures (titre,tag,couleur,ordre,actif) VALUES (?,?,?,?,?)")->execute([$titre,$tag,$couleur,$ordre,$actif]);
            $id = $db->lastInsertId();
        }
        $stmt = $db->prepare("INSERT INTO procedure_etapes (procedure_id,etape,ordre) VALUES (?,?,?)");
        foreach (array_values($etapes) as $i=>$e) $stmt->execute([$id,$e,$i]);
        $msg = 'Procédure enregistrée.'; $action='list'; $id=0;
    }
}

if (in_array($action,['edit']) && $id) {
    $proc   = $db->query("SELECT * FROM procedures WHERE id=$id")->fetch();
    $stmtE = $db->prepare("SELECT etape FROM procedure_etapes WHERE procedure_id=? ORDER BY ordre");
    $stmtE->execute([$id]);
    $etapes = $stmtE->fetchAll(PDO::FETCH_COLUMN);
} else {
    $proc = null; $etapes = [];
}
?>
<div class="page-header"><h1>📋 Procédures & guides</h1><a href="?action=add" class="btn btn-primary">+ Ajouter</a></div>
<?php if ($msg): ?><div class="alert alert-success py-2"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?=htmlspecialchars($err)?></div><?php endif; ?>

<?php if (in_array($action,['add','edit'])): ?>
<div class="card p-4 mb-4">
  <h5 class="fw-bold mb-3"><?=$id?'Modifier':'Nouvelle procédure'?></h5>
  <form method="post" action="?action=<?=$id?"edit&id=$id":'add'?>">
    <div class="row g-3">
      <div class="col-md-8"><label class="form-label">Titre *</label><input type="text" name="titre" class="form-control" value="<?=htmlspecialchars($proc['titre']??'')?>" required></div>
      <div class="col-md-2"><label class="form-label">Tag / Catégorie</label><input type="text" name="tag" class="form-control" value="<?=htmlspecialchars($proc['tag']??'')?>"></div>
      <div class="col-md-2"><label class="form-label">Couleur</label>
        <select name="couleur" class="form-select"><?php foreach($colors as $c): ?><option value="<?=$c?>" <?=($proc['couleur']??'')===$c?'selected':''?> style="background:<?=$c?>;color:#fff"><?=$c?></option><?php endforeach;?></select>
      </div>
      <div class="col-12"><label class="form-label">Étapes <small class="text-muted">(une par ligne)</small></label>
        <textarea name="etapes" class="form-control" rows="6" placeholder="Étape 1&#10;Étape 2&#10;Étape 3"><?=htmlspecialchars(implode("\n",$etapes))?></textarea>
      </div>
      <div class="col-md-2"><label class="form-label">Ordre</label><input type="number" name="ordre" class="form-control" value="<?=(int)($proc['ordre']??0)?>"></div>
      <div class="col-md-2 d-flex align-items-end"><div class="form-check mb-1"><input class="form-check-input" type="checkbox" name="actif" value="1" id="actif" <?=($proc['actif']??1)?'checked':''?>><label class="form-check-label" for="actif">Actif</label></div></div>
    </div>
    <div class="mt-3 d-flex gap-2"><button class="btn btn-primary">Enregistrer</button><a href="procedures.php" class="btn btn-outline-secondary">Annuler</a></div>
  </form>
</div>
<?php endif; ?>

<?php if ($action==='list'): ?>
<div class="card"><div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Titre</th><th>Tag</th><th>Étapes</th><th>Actif</th><th></th></tr></thead>
<tbody>
<?php
$procs = $db->query("SELECT p.*, COUNT(e.id) as nb_etapes FROM procedures p LEFT JOIN procedure_etapes e ON e.procedure_id=p.id GROUP BY p.id ORDER BY p.ordre,p.id")->fetchAll();
foreach($procs as $p):
?>
<tr style="<?=!$p['actif']?'opacity:.4':''?>">
  <td><div class="d-flex align-items-center gap-2"><div style="width:12px;height:12px;border-radius:3px;background:<?=htmlspecialchars($p['couleur'])?>"></div><strong><?=htmlspecialchars($p['titre'])?></strong></div></td>
  <td><span class="badge" style="background:<?=htmlspecialchars($p['couleur'])?>"><?=htmlspecialchars($p['tag'])?></span></td>
  <td><?=(int)$p['nb_etapes']?> étape(s)</td>
  <td><span class="badge bg-<?=$p['actif']?'success':'secondary'?>"><?=$p['actif']?'Oui':'Non'?></span></td>
  <td class="text-end"><a href="?action=edit&id=<?=$p['id']?>" class="btn btn-sm btn-outline-primary me-1">Modifier</a><a href="?action=delete&id=<?=$p['id']?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>
<?php require_once '_footer.php'; ?>
