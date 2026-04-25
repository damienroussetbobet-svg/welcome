<?php
$pageTitle = 'Section Bienvenue';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── Textes
    $fields = [
        'bienvenue_subtitle',
        'bienvenue_title',
        'bienvenue_text',
        'bienvenue_cta',
        'bienvenue_mot_btn',
        'bienvenue_mot_titre',
        'bienvenue_mot_accueil',
        'bienvenue_stat1_value', 'bienvenue_stat1_label',
        'bienvenue_stat2_value', 'bienvenue_stat2_label',
        'bienvenue_stat3_value', 'bienvenue_stat3_label',
    ];
    $stmt = $db->prepare("INSERT INTO site_config (cle, valeur) VALUES (?, ?) ON DUPLICATE KEY UPDATE valeur = VALUES(valeur)");
    foreach ($fields as $key) {
        $stmt->execute([$key, $_POST[$key] ?? '']);
    }

    // ── Upload vidéo
    if (!empty($_FILES['video']['name'])) {
        $allowed  = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];
        $mime     = $_FILES['video']['type'];
        $maxBytes = 200 * 1024 * 1024; // 200 Mo

        if (!in_array($mime, $allowed)) {
            $err = 'Format non supporté. Utilisez MP4, WebM ou OGG.';
        } elseif ($_FILES['video']['size'] > $maxBytes) {
            $err = 'Fichier trop volumineux (max 200 Mo).';
        } else {
            $ext      = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
            $filename = 'video_bienvenue_' . time() . '.' . $ext;
            $dest     = __DIR__ . '/../uploads/' . $filename;

            if (move_uploaded_file($_FILES['video']['tmp_name'], $dest)) {
                // Supprime l'ancienne vidéo
                $cfg = loadConfig();
                $old = $cfg['bienvenue_video'] ?? '';
                if ($old && file_exists(__DIR__ . '/../uploads/' . $old)) {
                    unlink(__DIR__ . '/../uploads/' . $old);
                }
                $stmt->execute(['bienvenue_video', $filename]);
            } else {
                $err = 'Échec du déplacement du fichier uploadé.';
            }
        }
    }

    // ── Suppression vidéo
    if (isset($_POST['delete_video'])) {
        $cfg = loadConfig();
        $old = $cfg['bienvenue_video'] ?? '';
        if ($old && file_exists(__DIR__ . '/../uploads/' . $old)) {
            unlink(__DIR__ . '/../uploads/' . $old);
        }
        $stmt->execute(['bienvenue_video', '']);
    }

    if (!$err) $msg = 'Section Bienvenue mise à jour.';
}

$cfg = loadConfig();
$currentVideo = $cfg['bienvenue_video'] ?? '';
?>

<div class="page-header">
  <h1>👋 Section Bienvenue</h1>
  <a href="../index.php" target="_blank" class="btn btn-outline-primary btn-sm">Voir le résultat →</a>
</div>

<?php if ($msg): ?><div class="alert alert-success py-2"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <div class="row g-4">

    <!-- Héro -->
    <div class="col-12">
      <div class="card p-4">
        <h6 class="fw-bold mb-3">🎯 Bloc principal</h6>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Badge / Sous-titre</label>
            <input type="text" name="bienvenue_subtitle" class="form-control"
              value="<?= htmlspecialchars($cfg['bienvenue_subtitle'] ?? '') ?>">
            <div class="form-text">Texte affiché dans la pastille bleue au-dessus du titre.</div>
          </div>
          <div class="col-12">
            <label class="form-label">Titre principal</label>
            <textarea name="bienvenue_title" class="form-control" rows="2"><?= htmlspecialchars($cfg['bienvenue_title'] ?? '') ?></textarea>
            <div class="form-text">Utilisez un saut de ligne pour forcer un retour à la ligne dans le titre.</div>
          </div>
          <div class="col-12">
            <label class="form-label">Texte d'accueil</label>
            <textarea name="bienvenue_text" class="form-control" rows="3"><?= htmlspecialchars($cfg['bienvenue_text'] ?? '') ?></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Texte du bouton CTA</label>
            <input type="text" name="bienvenue_cta" class="form-control"
              value="<?= htmlspecialchars($cfg['bienvenue_cta'] ?? '') ?>">
            <div class="form-text">Si une vidéo est associée, un bouton « Lecture » s'affichera à la place du lien.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mot d'accueil -->
    <div class="col-12">
      <div class="card p-4">
        <h6 class="fw-bold mb-1">📄 Mot d'accueil</h6>
        <p class="text-muted small mb-3">
          Un bouton « Mot d'accueil » s'affiche à côté du CTA principal et ouvre une fenêtre modale avec ce texte.
          Séparez les paragraphes par une ligne vide.
        </p>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Libellé du bouton</label>
            <input type="text" name="bienvenue_mot_btn" class="form-control"
              value="<?= htmlspecialchars($cfg['bienvenue_mot_btn'] ?? '') ?>"
              placeholder="Mot d'accueil">
          </div>
          <div class="col-md-6">
            <label class="form-label">Titre de la fenêtre</label>
            <input type="text" name="bienvenue_mot_titre" class="form-control"
              value="<?= htmlspecialchars($cfg['bienvenue_mot_titre'] ?? '') ?>"
              placeholder="Mot d'accueil">
          </div>
          <div class="col-12">
            <label class="form-label">Contenu du mot d'accueil</label>
            <textarea name="bienvenue_mot_accueil" class="form-control" rows="12"
              style="font-size:13px;line-height:1.65"><?= htmlspecialchars($cfg['bienvenue_mot_accueil'] ?? '') ?></textarea>
            <div class="form-text">Laissez une ligne vide entre chaque paragraphe pour qu'ils s'affichent séparément.</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Vidéo -->
    <div class="col-12">
      <div class="card p-4">
        <h6 class="fw-bold mb-3">🎬 Vidéo de présentation</h6>
        <p class="text-muted small mb-3">
          Quand une vidéo est uploadée, le bouton CTA ouvre une modale de lecture au lieu de faire défiler la page.
          Formats acceptés : MP4, WebM, OGG — max 200 Mo.
        </p>

        <?php if ($currentVideo && file_exists(__DIR__ . '/../uploads/' . $currentVideo)): ?>
        <div class="mb-3 p-3 rounded-3" style="background:#F0F4FF;border:1.5px solid #BDD0FF">
          <div class="fw-semibold mb-2" style="font-size:13px">📹 Vidéo actuelle</div>
          <video src="../uploads/<?= htmlspecialchars($currentVideo) ?>" controls
            style="max-width:100%;max-height:220px;border-radius:10px;display:block;margin-bottom:10px"></video>
          <div class="d-flex align-items-center gap-3">
            <span class="text-muted small"><?= htmlspecialchars($currentVideo) ?></span>
            <button type="submit" name="delete_video" value="1"
              class="btn btn-sm btn-outline-danger"
              onclick="return confirm('Supprimer la vidéo ?')">🗑 Supprimer la vidéo</button>
          </div>
        </div>
        <?php else: ?>
        <div class="mb-3 p-3 rounded-3 text-muted text-center" style="background:#F7F9FF;border:1.5px dashed #C5CFEE;font-size:13px">
          Aucune vidéo uploadée — le bouton CTA fera défiler la page vers la section Service.
        </div>
        <?php endif; ?>

        <div>
          <label class="form-label">
            <?= $currentVideo ? 'Remplacer la vidéo' : 'Uploader une vidéo' ?>
          </label>
          <input type="file" name="video" class="form-control" accept="video/mp4,video/webm,video/ogg">
        </div>
      </div>
    </div>

    <!-- Stats -->
    <div class="col-12">
      <div class="card p-4">
        <h6 class="fw-bold mb-3">📊 Cartes statistiques</h6>
        <div class="row g-3">
          <?php
          $statDefs = [
            [1, 'Stat 1', '#00A8D6'],
            [2, 'Stat 2', '#F5A020'],
            [3, 'Stat 3', '#8A6FE8'],
          ];
          foreach ($statDefs as [$n, $lbl, $color]):
          ?>
          <div class="col-md-4">
            <div class="p-3 rounded-3" style="border:2px solid <?= $color ?>22;background:<?= $color ?>0D">
              <div class="fw-bold mb-2" style="font-size:12px;color:<?= $color ?>;text-transform:uppercase;letter-spacing:.05em"><?= $lbl ?></div>
              <div class="mb-2">
                <label class="form-label mb-1" style="font-size:12px">Valeur (grande)</label>
                <input type="text" name="bienvenue_stat<?= $n ?>_value" class="form-control form-control-sm"
                  value="<?= htmlspecialchars($cfg["bienvenue_stat{$n}_value"] ?? '') ?>"
                  placeholder="ex : ~180, 24h/24…">
              </div>
              <div>
                <label class="form-label mb-1" style="font-size:12px">Label (petit)</label>
                <input type="text" name="bienvenue_stat<?= $n ?>_label" class="form-control form-control-sm"
                  value="<?= htmlspecialchars($cfg["bienvenue_stat{$n}_label"] ?? '') ?>"
                  placeholder="ex : agents DSN">
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="col-12">
      <button class="btn btn-primary px-4">💾 Enregistrer</button>
      <a href="../index.php" target="_blank" class="btn btn-outline-secondary ms-2">Prévisualiser →</a>
    </div>

  </div>
</form>

<?php require_once '_footer.php'; ?>
