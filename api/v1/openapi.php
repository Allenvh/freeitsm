<?php
/**
 * FreeITSM REST API v1 — serves the OpenAPI description document.
 *
 *   GET /api/v1/openapi.json   (or .../openapi.php)
 *   GET /api/v1/openapi.yaml   (or .../openapi.php?format=yaml)
 *
 * This is public documentation — no key required — so tooling (Swagger UI,
 * Postman, client generators) can fetch it directly. It contains no secrets:
 * only the shape of the API, which any documentation describes. Generated
 * fresh on each request from the live route table + docs catalogue, so it can
 * never fall out of step with the API it describes.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/lib/openapi.php';

$format = $_GET['format'] ?? 'json';
if ($format === '' ) $format = 'json';

// Allow cross-origin fetches (read-only, no credentials) so browser-based
// spec viewers can load it.
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache');

try {
    $doc = apiV1BuildOpenApi();
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => ['code' => 'openapi_generation_failed', 'message' => $e->getMessage()]]);
    exit;
}

if (strtolower($format) === 'yaml' || strtolower($format) === 'yml') {
    header('Content-Type: application/yaml; charset=utf-8');
    echo apiV1OpenApiToYaml($doc);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
