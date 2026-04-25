<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();

require_once '../api/config.php';

/* ── Credentials : DB en priorité, constantes en fallback ── */
function getAdminCredentials(): array {
    try {
        $cfg  = loadConfig();
        $login = $cfg['admin_login']         ?? ADMIN_LOGIN;
        $hash  = $cfg['admin_password_hash'] ?? ADMIN_PASSWORD_HASH;
        return [$login, $hash];
    } catch (Throwable $e) {
        return [ADMIN_LOGIN, ADMIN_PASSWORD_HASH];
    }
}

/* ── Déjà connecté ── */
if (isset($_SESSION['admin_ok'])) {
    header('Location: index.php');
    exit;
}

/* ── Gestion du brute-force : max 5 essais, blocage 15 min ── */
$maxAttempts  = 5;
$lockDuration = 900; // secondes
$attempts     = $_SESSION['login_attempts']  ?? 0;
$lastFail     = $_SESSION['login_last_fail'] ?? 0;
$locked       = $attempts >= $maxAttempts && (time() - $lastFail) < $lockDuration;

/* ── CSRF token ── */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$locked) {
    /* Vérification CSRF */
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error = 'Requête invalide.';
    } else {
        $login    = trim($_POST['login']    ?? '');
        $password = trim($_POST['password'] ?? '');

        [$adminLogin, $adminHash] = getAdminCredentials();

        $loginOk = hash_equals($adminLogin, $login);
        $passOk  = password_verify($password, $adminHash);

        if ($loginOk && $passOk) {
            /* Succès : régénérer la session et réinitialiser les compteurs */
            session_regenerate_id(true);
            $_SESSION['admin_ok']        = true;
            $_SESSION['admin_login']     = $login;
            $_SESSION['login_attempts']  = 0;
            $_SESSION['login_last_fail'] = 0;
            unset($_SESSION['csrf_token']);
            header('Location: index.php');
            exit;
        }

        /* Échec */
        $_SESSION['login_attempts']  = ($attempts + 1);
        $_SESSION['login_last_fail'] = time();
        $attempts++;
        $locked = $attempts >= $maxAttempts;

        /* Message volontairement vague (ne révèle pas si c'est le login ou le mdp) */
        $error = $locked
            ? 'Trop de tentatives. Compte bloqué 15 minutes.'
            : 'Identifiants incorrects.';
    }
}

$remainingMin = $locked ? ceil(($lockDuration - (time() - $lastFail)) / 60) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion – Admin DSN</title>
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { background:#E8EDFF; display:flex; align-items:center; justify-content:center; min-height:100vh; font-family:'Segoe UI',system-ui,sans-serif; }
  .card { border:none; border-radius:20px; box-shadow:0 8px 32px rgba(27,58,122,.13); padding:40px 36px; width:380px; }
  .btn-primary { background:#1B3A7A; border-color:#1B3A7A; border-radius:24px; padding:10px; font-weight:700; }
  .btn-primary:disabled { background:#6B7BA8; border-color:#6B7BA8; }
  .form-control { border-radius:10px; }
</style>
</head>
<body>
<div class="card">
  <div class="text-center mb-4">
    <img src="../logo-chu.png" alt="CHU Angers" style="height:40px; margin-bottom:14px">
    <h5 class="fw-bold text-dark">Administration – Livret DSN</h5>
  </div>

  <?php if ($locked): ?>
    <div class="alert alert-danger py-2 small">
      Trop de tentatives échouées. Réessayez dans <?= $remainingMin ?> minute<?= $remainingMin > 1 ? 's' : '' ?>.
    </div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

    <div class="mb-3">
      <label class="form-label fw-semibold">Identifiant</label>
      <input type="text" name="login" class="form-control"
        autocomplete="username" autofocus required
        <?= $locked ? 'disabled' : '' ?>>
    </div>
    <div class="mb-4">
      <label class="form-label fw-semibold">Mot de passe</label>
      <input type="password" name="password" class="form-control"
        autocomplete="current-password" required
        <?= $locked ? 'disabled' : '' ?>>
    </div>

    <button class="btn btn-primary w-100" <?= $locked ? 'disabled' : '' ?>>Se connecter</button>
  </form>

  <?php if ($attempts > 0 && !$locked): ?>
    <div class="text-center mt-2 small text-muted">
      <?= $maxAttempts - $attempts ?> tentative<?= ($maxAttempts - $attempts) > 1 ? 's' : '' ?> restante<?= ($maxAttempts - $attempts) > 1 ? 's' : '' ?>
    </div>
  <?php endif; ?>

  <div class="text-center mt-3"><a href="../index.php" class="small text-muted">← Retour au livret</a></div>
</div>
</body>
</html>
