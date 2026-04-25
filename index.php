<?php
require_once 'api/config.php';
try {
    $siteData = loadSiteData();
} catch (Throwable $e) {
    header('Location: setup.php');
    exit;
}
$siteDataJson = json_encode($siteData, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Livret d'accueil – DSN CHU Angers</title>
<link rel="icon" href="favicon.svg" type="image/svg+xml">
<link href="assets/css/fonts.css" rel="stylesheet">
<script src="assets/js/react.production.min.js"></script>
<script src="assets/js/react-dom.production.min.js"></script>
<script src="assets/js/lucide.min.js"></script>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html { scroll-behavior: smooth; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: #E8EDFF; color: #0F1E45; min-height: 100vh; display: flex; }
  ::-webkit-scrollbar { width: 6px; }
  ::-webkit-scrollbar-thumb { background: #C5CFEE; border-radius: 6px; }
  #root { display: flex; width: 100%; }
  a { text-decoration: none; }
  /* Rendu du mot d'accueil (contenu Quill) */
  .mot-accueil-body { font-size:14.5px; color:#3D4A6A; line-height:1.85; }
  .mot-accueil-body p { margin:0 0 18px; }
  .mot-accueil-body p:last-child { margin-bottom:0; }
  .mot-accueil-body h2 { font-size:17px; font-weight:800; color:#0F1E45; margin:24px 0 10px; }
  .mot-accueil-body h3 { font-size:15px; font-weight:700; color:#1B3A7A; margin:20px 0 8px; }
  .mot-accueil-body strong { font-weight:700; color:#0F1E45; }
  .mot-accueil-body em { font-style:italic; }
  .mot-accueil-body u { text-decoration:underline; text-underline-offset:3px; }
  .mot-accueil-body ul, .mot-accueil-body ol { padding-left:22px; margin:0 0 18px; }
  .mot-accueil-body li { margin-bottom:6px; }
  .mot-accueil-body li:last-child { margin-bottom:0; }
</style>
</head>
<body>
<div id="root"></div>
<script>window.SITE_DATA = <?= $siteDataJson ?>;</script>
<script src="assets/js/app.js?v=<?= filemtime(__DIR__.'/assets/js/app.js') ?>"></script>
</body>
</html>
