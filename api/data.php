<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config.php';

try {
    echo json_encode(loadSiteData(), JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
