<?php
session_start();
require_once '../api/config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['admin_ok'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Mot de passe incorrect.';
}
if (isset($_SESSION['admin_ok'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion – Admin DSN</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { background:#E8EDFF; display:flex; align-items:center; justify-content:center; min-height:100vh; font-family:'Segoe UI',system-ui,sans-serif; }
  .card { border:none; border-radius:20px; box-shadow:0 8px 32px rgba(27,58,122,.13); padding:40px 36px; width:360px; }
  .btn-primary { background:#1B3A7A; border-color:#1B3A7A; border-radius:24px; padding:10px; font-weight:700; }
  .form-control { border-radius:10px; }
</style>
</head>
<body>
<div class="card">
  <div class="text-center mb-4">
    <img src="../logo-chu.png" alt="CHU Angers" style="height:40px; margin-bottom:14px">
    <h5 class="fw-bold text-dark">Administration – Livret DSN</h5>
  </div>
  <?php if ($error): ?>
    <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label fw-semibold">Mot de passe</label>
      <input type="password" name="password" class="form-control" autofocus required>
    </div>
    <button class="btn btn-primary w-100">Se connecter</button>
  </form>
  <div class="text-center mt-3"><a href="../index.php" class="small text-muted">← Retour au livret</a></div>
</div>
</body>
</html>
