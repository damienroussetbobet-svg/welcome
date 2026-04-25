<?php
// Shared admin header/footer. Include at top of each admin page.
// Requires $pageTitle to be defined before inclusion.
if (!defined('IN_ADMIN')) define('IN_ADMIN', true);
session_start();
if (!isset($_SESSION['admin_ok'])) {
    header('Location: login.php');
    exit;
}
$pageTitle ??= 'Administration';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> – Admin DSN</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body        { background: #F0F4FF; font-family: 'Segoe UI', system-ui, sans-serif; }
  .sidebar    { width: 220px; min-height: 100vh; background: #0F1E45; position: fixed; top:0; left:0; padding-top:20px; z-index:100; }
  .sidebar .brand { padding:12px 20px 20px; border-bottom:1px solid rgba(255,255,255,0.1); margin-bottom:8px; }
  .sidebar .brand span { font-size:11px; font-weight:700; color:#BDD0FF; letter-spacing:.06em; display:block; margin-top:4px; }
  .sidebar a  { display:flex; align-items:center; gap:10px; padding:9px 20px; color:rgba(255,255,255,.65); font-size:13.5px; text-decoration:none; transition:.15s; }
  .sidebar a:hover, .sidebar a.active { background:rgba(255,255,255,.08); color:#fff; }
  .sidebar a .ico { font-size:16px; width:22px; text-align:center; }
  .main       { margin-left:220px; padding:28px; }
  .page-header{ display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; }
  .page-header h1 { font-size:22px; font-weight:800; color:#0F1E45; margin:0; }
  .card       { border:none; border-radius:16px; box-shadow:0 2px 12px rgba(27,58,122,.08); }
  .btn-primary{ background:#1B3A7A; border-color:#1B3A7A; }
  .btn-primary:hover{ background:#0F1E45; border-color:#0F1E45; }
  .badge-pole { font-size:11px; padding:3px 10px; border-radius:20px; font-weight:700; }
  .table th   { font-size:12px; text-transform:uppercase; letter-spacing:.04em; color:#6B7BA8; border-bottom:2px solid #E8EDFF; }
  .table td   { vertical-align:middle; font-size:13.5px; }
  .form-label { font-size:13px; font-weight:600; color:#0F1E45; }
  .alert      { border-radius:12px; }
</style>
</head>
<body>
<div class="sidebar">
  <div class="brand">
    <img src="../logo-chu.png" alt="CHU" style="height:28px">
    <span>ADMIN · DSN</span>
  </div>
  <?php
  $nav = [
    ['index.php',          '🏠', 'Tableau de bord'],
    ['bienvenue.php',      '👋', 'Bienvenue'],
    ['agents.php',         '👤', 'Annuaire / Agents'],
    ['organigramme.php',   '🏗️', 'Organigramme'],
    ['contacts.php',       '📞', 'Contacts'],
    ['faq.php',            '❓', 'FAQ'],
    ['procedures.php',     '📋', 'Procédures'],
    ['outils.php',         '🛠️', 'Outils'],
    ['ressources.php',     '🔗', 'Ressources'],
    ['planning.php',       '📅', 'Planning'],
    ['pratique.php',       'ℹ️', 'Infos pratiques'],
  ];
  $cur = basename($_SERVER['PHP_SELF']);
  foreach ($nav as [$href, $ico, $lbl]):
  ?>
  <a href="<?= $href ?>" class="<?= $cur === $href ? 'active' : '' ?>">
    <span class="ico"><?= $ico ?></span><?= $lbl ?>
  </a>
  <?php endforeach; ?>
  <hr style="border-color:rgba(255,255,255,.1); margin:12px 0">
  <a href="../index.php" target="_blank"><span class="ico">🌐</span>Voir le livret</a>
  <a href="logout.php"><span class="ico">🚪</span>Déconnexion</a>
</div>
<div class="main">
