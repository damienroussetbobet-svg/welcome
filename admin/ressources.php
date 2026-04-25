<?php
$pageTitle = 'Ressources & liens';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = $err = '';
$colors = ['#00A8D6','#8A6FE8','#F5A020','#5CB85C','#1B3A7A','#D63030'];
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

if ($action==='delete' && $id) { $db->prepare("DELETE FROM ressources_categories WHERE id=?")->execute([$id]); $msg='Catégorie supprimée.'; $action='list'; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat    = trim($_POST['categorie']??'');
    $icone  = trim($_POST['icone']??'link');
    $couleur= $_POST['couleur']??'#00A8D6';
    $ordre  = (int)($_POST['ordre']??0);
    $actif  = (int)($_POST['actif']??1);
    // Parse liens: label|description|url (one per line)
    $rawLines = array_filter(array_map('trim', explode("\n", $_POST['liens']??'')));
    $liens = [];
    foreach($rawLines as $line) {
        $parts = array_map('trim', explode('|', $line));
        if (!empty($parts[0])) $liens[] = [$parts[0], $parts[1]??'', $parts[2]??'#'];
    }

    if (empty($cat)) { $err='La catégorie est requise.'; }
    else {
        if ($id) {
            $db->prepare("UPDATE ressources_categories SET categorie=?,icone=?,couleur=?,ordre=?,actif=? WHERE id=?")->execute([$cat,$icone,$couleur,$ordre,$actif,$id]);
            $db->prepare("DELETE FROM ressources_liens WHERE categorie_id=?")->execute([$id]);
        } else {
            $db->prepare("INSERT INTO ressources_categories (categorie,icone,couleur,ordre,actif) VALUES (?,?,?,?,?)")->execute([$cat,$icone,$couleur,$ordre,$actif]);
            $id=$db->lastInsertId();
        }
        $stmt=$db->prepare("INSERT INTO ressources_liens (categorie_id,label,description,url,ordre) VALUES (?,?,?,?,?)");
        foreach(array_values($liens) as $i=>$l) $stmt->execute([$id,$l[0],$l[1],$l[2],$i]);
        $msg='Catégorie enregistrée.'; $action='list'; $id=0;
    }
}

if (in_array($action,['edit']) && $id) {
    $cat_row = $db->query("SELECT * FROM ressources_categories WHERE id=$id")->fetch();
    $liens   = $db->query("SELECT label,description,url FROM ressources_liens WHERE categorie_id=$id ORDER BY ordre")->fetchAll();
    $liensText = implode("\n", array_map(fn($l)=>"{$l['label']}|{$l['description']}|{$l['url']}", $liens));
} else { $cat_row=null; $liensText=''; }
?>
<div class="page-header"><h1>🔗 Ressources & liens</h1><a href="?action=add" class="btn btn-primary">+ Ajouter une catégorie</a></div>
<?php if ($msg): ?><div class="alert alert-success py-2"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?=htmlspecialchars($err)?></div><?php endif; ?>

<?php if (in_array($action,['add','edit'])): ?>
<div class="card p-4 mb-4">
  <h5 class="fw-bold mb-3"><?=$id?'Modifier':'Nouvelle catégorie'?></h5>
  <form method="post" action="?action=<?=$id?"edit&id=$id":'add'?>">
    <div class="row g-3">
      <div class="col-md-4"><label class="form-label">Catégorie *</label><input type="text" name="categorie" class="form-control" value="<?=htmlspecialchars($cat_row['categorie']??'')?>" required></div>
      <div class="col-md-3"><label class="form-label">Icône Lucide</label><input type="text" name="icone" class="form-control" placeholder="globe, book-open, users…" value="<?=htmlspecialchars($cat_row['icone']??'link')?>"></div>
      <div class="col-md-3"><label class="form-label">Couleur</label>
        <select name="couleur" class="form-select"><?php foreach($colors as $c): ?><option value="<?=$c?>" <?=($cat_row['couleur']??'')===$c?'selected':''?> style="background:<?=$c?>;color:#fff"><?=$c?></option><?php endforeach;?></select>
      </div>
      <div class="col-md-2"><label class="form-label">Ordre</label><input type="number" name="ordre" class="form-control" value="<?=(int)($cat_row['ordre']??0)?>"></div>
      <div class="col-12">
        <label class="form-label">Liens <small class="text-muted">(format : <code>Libellé|Description|https://url.fr</code>, un par ligne)</small></label>
        <textarea name="liens" class="form-control" rows="5" placeholder="Intranet CHU Angers|Portail agent|https://intranet.chu-angers.fr"><?=htmlspecialchars($liensText??'')?></textarea>
      </div>
      <div class="col-md-2 d-flex align-items-end"><div class="form-check mb-1"><input class="form-check-input" type="checkbox" name="actif" value="1" id="actif" <?=($cat_row['actif']??1)?'checked':''?>><label class="form-check-label" for="actif">Actif</label></div></div>
    </div>
    <div class="mt-3 d-flex gap-2"><button class="btn btn-primary">Enregistrer</button><a href="ressources.php" class="btn btn-outline-secondary">Annuler</a></div>
  </form>
</div>
<?php endif; ?>

<?php if ($action==='list'): ?>
<div class="card"><div class="table-responsive"><table class="table mb-0">
<thead><tr><th>Catégorie</th><th>Icône</th><th>Nombre de liens</th><th>Actif</th><th></th></tr></thead>
<tbody>
<?php
$cats = $db->query("SELECT c.*,COUNT(l.id) as nb FROM ressources_categories c LEFT JOIN ressources_liens l ON l.categorie_id=c.id GROUP BY c.id ORDER BY c.ordre,c.id")->fetchAll();
foreach($cats as $c):
?>
<tr style="<?=!$c['actif']?'opacity:.4':''?>">
  <td><div class="d-flex align-items-center gap-2"><div style="width:10px;height:10px;border-radius:3px;background:<?=htmlspecialchars($c['couleur'])?>"></div><strong><?=htmlspecialchars($c['categorie'])?></strong></div></td>
  <td><code><?=htmlspecialchars($c['icone'])?></code></td>
  <td><?=(int)$c['nb']?> lien(s)</td>
  <td><span class="badge bg-<?=$c['actif']?'success':'secondary'?>"><?=$c['actif']?'Oui':'Non'?></span></td>
  <td class="text-end"><a href="?action=edit&id=<?=$c['id']?>" class="btn btn-sm btn-outline-primary me-1">Modifier</a><a href="?action=delete&id=<?=$c['id']?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>
<?php require_once '_footer.php'; ?>
