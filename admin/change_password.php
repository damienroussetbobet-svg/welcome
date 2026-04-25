<?php
$pageTitle = 'Changer les identifiants';
require_once '_layout.php';
require_once '../api/config.php';
$db  = getDB();
$msg = $err = '';

/* ── Récupère les credentials actuels (DB ou constantes) ── */
function getCurrentCredentials(): array {
    try {
        $cfg = loadConfig();
        return [
            $cfg['admin_login']         ?? ADMIN_LOGIN,
            $cfg['admin_password_hash'] ?? ADMIN_PASSWORD_HASH,
        ];
    } catch (Throwable $e) {
        return [ADMIN_LOGIN, ADMIN_PASSWORD_HASH];
    }
}

/* ── Règles de complexité du mot de passe ── */
function validatePassword(string $p): string {
    if (strlen($p) < 10)                        return 'Au moins 10 caractères requis.';
    if (!preg_match('/[A-Z]/', $p))             return 'Au moins une majuscule requise.';
    if (!preg_match('/[a-z]/', $p))             return 'Au moins une minuscule requise.';
    if (!preg_match('/[0-9]/', $p))             return 'Au moins un chiffre requis.';
    if (!preg_match('/[\W_]/', $p))             return 'Au moins un caractère spécial requis (!@#$%…).';
    return '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$currentLogin, $currentHash] = getCurrentCredentials();

    $currentPwd  = $_POST['current_password'] ?? '';
    $newLogin    = trim($_POST['new_login']    ?? '');
    $newPwd      = $_POST['new_password']      ?? '';
    $confirmPwd  = $_POST['confirm_password']  ?? '';

    if (!password_verify($currentPwd, $currentHash)) {
        $err = 'Mot de passe actuel incorrect.';
    } elseif (empty($newLogin)) {
        $err = 'L\'identifiant ne peut pas être vide.';
    } elseif ($newPwd !== $confirmPwd) {
        $err = 'Les deux nouveaux mots de passe ne correspondent pas.';
    } elseif (($pwdErr = validatePassword($newPwd)) !== '') {
        $err = $pwdErr;
    } else {
        $newHash = password_hash($newPwd, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt    = $db->prepare("INSERT INTO site_config (cle,valeur) VALUES (?,?) ON DUPLICATE KEY UPDATE valeur=VALUES(valeur)");
        $stmt->execute(['admin_login',         $newLogin]);
        $stmt->execute(['admin_password_hash', $newHash]);
        $msg = 'Identifiants mis à jour avec succès.';
    }
}
?>

<div class="page-header">
  <h1>🔐 Changer les identifiants</h1>
</div>

<?php if ($msg): ?><div class="alert alert-success py-2"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger py-2"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card p-4">
      <form method="post" autocomplete="off">

        <div class="mb-4 pb-3" style="border-bottom:1px solid #E8EDFF">
          <label class="form-label">Mot de passe actuel</label>
          <input type="password" name="current_password" class="form-control" autocomplete="current-password" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Nouvel identifiant</label>
          <input type="text" name="new_login" class="form-control"
            value="<?= htmlspecialchars($_POST['new_login'] ?? '') ?>"
            autocomplete="off" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Nouveau mot de passe</label>
          <input type="password" name="new_password" class="form-control"
            autocomplete="new-password" required
            oninput="checkStrength(this.value)">
          <!-- Indicateur de force -->
          <div class="mt-2" id="strength-bar" style="height:4px;border-radius:4px;background:#E8EDFF;overflow:hidden">
            <div id="strength-fill" style="height:100%;width:0;transition:width .3s,background .3s"></div>
          </div>
          <div id="strength-label" class="form-text mt-1"></div>
        </div>

        <div class="mb-4">
          <label class="form-label">Confirmer le nouveau mot de passe</label>
          <input type="password" name="confirm_password" class="form-control"
            autocomplete="new-password" required>
        </div>

        <div class="alert alert-info py-2 small mb-4">
          Le mot de passe doit contenir au moins <strong>10 caractères</strong>,
          une majuscule, une minuscule, un chiffre et un caractère spécial.
        </div>

        <button class="btn btn-primary px-4">💾 Mettre à jour</button>
        <a href="index.php" class="btn btn-outline-secondary ms-2">Annuler</a>
      </form>
    </div>
  </div>
</div>

<script>
function checkStrength(v) {
  let score = 0;
  if (v.length >= 10)              score++;
  if (/[A-Z]/.test(v))            score++;
  if (/[a-z]/.test(v))            score++;
  if (/[0-9]/.test(v))            score++;
  if (/[\W_]/.test(v))            score++;

  const fill  = document.getElementById('strength-fill');
  const label = document.getElementById('strength-label');
  const pct   = (score / 5 * 100) + '%';
  const colors = ['#D63030','#F5A020','#F5A020','#5CB85C','#00A8D6'];
  const labels = ['Très faible','Faible','Moyen','Fort','Très fort'];
  fill.style.width      = pct;
  fill.style.background = colors[score - 1] || '#E8EDFF';
  label.textContent     = score > 0 ? labels[score - 1] : '';
  label.style.color     = colors[score - 1] || '#6B7BA8';
}
</script>

<?php require_once '_footer.php'; ?>
