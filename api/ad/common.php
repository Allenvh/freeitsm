<?php
session_start();
require_once __DIR__.'/../../config.php';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../../includes/ad.php';
header('Content-Type: application/json');
header('Cache-Control: no-store');
function adInput(): array {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new RuntimeException('POST required');
    $data = json_decode(file_get_contents('php://input'),true,32,JSON_THROW_ON_ERROR);
    if (!is_array($data)) throw new RuntimeException('Invalid request');
    adCheckCsrf($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    return $data;
}
