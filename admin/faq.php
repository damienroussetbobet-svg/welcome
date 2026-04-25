<?php
$pageTitle = 'FAQ';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = $err = '';
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

if ($action==='delete' && $id) { $db->prepare("DELETE FROM faq WHERE id=?")->execute([$id]); $msg='Question supprimée.'; $action='list'; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['helpdesk_email'])) {
        $hFields = ['helpdesk_email','helpdesk_disponibilite','helpdesk_cta','helpdesk_faq_titre'];
        $stmt = $db->prepare("INSERT INTO site_config (cle,valeur) VALUES (?,?) ON DUPLICATE KEY UPDATE valeur=VALUES(valeur)");
        foreach ($hFields as $key) $stmt->execute([$key, $_POST[$key] ?? '']);
        $msg = 'Paramètres helpdesk mis à jour.';
    } else {
        $q = trim($_POST['question']??'');
        $r = trim($_POST['reponse']??'');
        $o = (int)($_POST['ordre']??0);
        $a = (int)($_POST['actif']??1);
        if (empty($q)) { $err='La question est requise.'; }
        else {
            if ($id) { $db->prepare("UPDATE faq SET question=?,reponse=?,ordre=?,actif=? WHERE id=?")->execute([$q,$r,$o,$a,$id]); $msg='FAQ mise à jour.'; }
            else { $db->prepare("INSERT INTO faq (question,reponse,ordre,actif) VALUES (?,?,?,?)")->execute([$q,$r,$o,$a]); $msg='Question ajoutée.'; }
            $action='list'; $id=0;
        }
    }
}
$item = ($id && in_array($action,['edit'])) ? $db->query("SELECT * FROM faq WHERE id=$id")->fetch() : null;
$cfg  = loadConfig();
?>
<div class="page-header"><h1>❓ FAQ</h1><a href="?action=add" class="btn btn-primary">+ Ajouter une question</a></div>
<?php if ($msg): ?><div class="alert alert-success py-2"><?=htmlspecialchars($msg)?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?=htmlspecialchars($err)?></div><?php endif; ?>

<!-- ── Helpdesk config ── -->
<div class="card p-4 mb-4">
  <h6 class="fw-bold mb-3">📞 Bloc Helpdesk (bannière FAQ + sidebar)</h6>
  <form method="post">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">E-mail helpdesk</label>
        <input type="text" name="helpdesk_email" class="form-control"
          value="<?=htmlspecialchars($cfg['helpdesk_email'] ?? 'helpdesk@chu-angers.fr')?>">
        <div class="form-text">Utilisé comme lien mailto dans la sidebar et la bannière FAQ.</div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Disponibilité</label>
        <input type="text" name="helpdesk_disponibilite" class="form-control"
          value="<?=htmlspecialchars($cfg['helpdesk_disponibilite'] ?? 'lun–ven 7h30–18h30')?>">
        <div class="form-text">Affiché dans la sidebar et sous le titre de la bannière FAQ.</div>
      </div>
      <div class="col-md-6">
        <label class="form-label">Titre bannière FAQ</label>
        <input type="text" name="helpdesk_faq_titre" class="form-control"
          value="<?=htmlspecialchars($cfg['helpdesk_faq_titre'] ?? 'Vous n\'avez pas trouvé votre réponse ?')?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Texte du bouton</label>
        <input type="text" name="helpdesk_cta" class="form-control"
          value="<?=htmlspecialchars($cfg['helpdesk_cta'] ?? 'Contacter le helpdesk')?>">
      </div>
    </div>
    <div class="mt-3">
      <button class="btn btn-primary">💾 Enregistrer</button>
    </div>
  </form>
</div>

<?php if (in_array($action,['add','edit'])): ?>
<div class="card p-4 mb-4">
  <h5 class="fw-bold mb-3"><?=$id?'Modifier':'Nouvelle question'?></h5>
  <form method="post" action="?action=<?=$id?"edit&id=$id":'add'?>">
    <div class="mb-3"><label class="form-label">Question *</label><input type="text" name="question" class="form-control" value="<?=htmlspecialchars($item['question']??'')?>" required></div>
    <div class="mb-3"><label class="form-label">Réponse</label><textarea name="reponse" class="form-control" rows="4"><?=htmlspecialchars($item['reponse']??'')?></textarea></div>
    <div class="row g-3">
      <div class="col-md-2"><label class="form-label">Ordre</label><input type="number" name="ordre" class="form-control" value="<?=(int)($item['ordre']??0)?>"></div>
      <div class="col-md-2 d-flex align-items-end"><div class="form-check mb-1"><input class="form-check-input" type="checkbox" name="actif" value="1" id="actif" <?=($item['actif']??1)?'checked':''?>><label class="form-check-label" for="actif">Actif</label></div></div>
    </div>
    <div class="mt-3 d-flex gap-2"><button class="btn btn-primary">Enregistrer</button><a href="faq.php" class="btn btn-outline-secondary">Annuler</a></div>
  </form>
</div>
<?php endif; ?>

<?php if ($action==='list'): ?>
<div class="card"><div class="table-responsive"><table class="table mb-0">
<thead><tr><th>#</th><th>Question</th><th>Réponse (extrait)</th><th>Actif</th><th></th></tr></thead>
<tbody>
<?php foreach($db->query("SELECT * FROM faq ORDER BY ordre,id")->fetchAll() as $i=>$f): ?>
<tr style="<?=!$f['actif']?'opacity:.4':''?>">
  <td><?=($i+1)?></td>
  <td><strong style="font-size:13px"><?=htmlspecialchars($f['question'])?></strong></td>
  <td style="font-size:12px;color:#6B7BA8"><?=htmlspecialchars(mb_substr($f['reponse']??'',0,80))?>…</td>
  <td><span class="badge bg-<?=$f['actif']?'success':'secondary'?>"><?=$f['actif']?'Oui':'Non'?></span></td>
  <td class="text-end"><a href="?action=edit&id=<?=$f['id']?>" class="btn btn-sm btn-outline-primary me-1">Modifier</a><a href="?action=delete&id=<?=$f['id']?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>
<?php require_once '_footer.php'; ?>
