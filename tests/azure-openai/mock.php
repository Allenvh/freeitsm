<?php
/* 🔴 Served ONLY by the built-in server that tests/azure-openai/run.php starts
   with `php -S`. Under Apache, nginx or php-fpm this is an unauthenticated
   endpoint that writes a file to disk, so it refuses there. */
if (PHP_SAPI !== 'cli-server') { http_response_code(404); exit; }

/**
 * A stand-in for an Azure OpenAI deployment-based endpoint.
 *
 * 🔴 WHY THIS EXISTS. Nobody here has an Azure subscription, so the question
 * "does Azure accept our request?" cannot be answered locally. But that is not
 * the question the new code raises. Azure's request body and response are
 * byte-for-byte the OpenAI ones we have shipped for months; what is NEW is
 * exactly three things — the URL shape, the auth header, and the absence of a
 * `model` field — and all three are things WE emit. This records them so they
 * can be asserted.
 *
 * ⚠️ What it CANNOT prove: anything Azure does at its end. Content filtering,
 * api-version drift, quota shapes, regional behaviour. Those need a real tenant,
 * and the honest answer is that the reporter has one and we do not.
 *
 * Behaviour is switched by the deployment name in the URL, so a test can ask for
 * a failure without a second file:
 *   ok                     → a normal successful completion
 *   needs-max-completion   → the 400 a newer deployment returns for `max_tokens`
 *   needs-default-temperature → the 400 gpt-5-class models return for temperature 0
 *   gpt5-like              → both of the above, one after the other (GH #171)
 *   content-filtered       → an UNRELATED 400 that must never be retried
 * and the v1 route (/openai/v1/chat/completions), which has no deployment in
 * the address and must carry it as `model` instead.
 */

$raw     = file_get_contents('php://input');
$body    = json_decode($raw, true) ?: [];
$uri     = $_SERVER['REQUEST_URI'] ?? '';
$headers = function_exists('getallheaders') ? getallheaders() : [];

// Normalise header names — PHP servers differ on case.
$h = [];
foreach ($headers as $k => $v) { $h[strtolower($k)] = $v; }

// Record everything the client sent, for the assertions in run.php.
// ⚠️ Beside the mock, NOT in sys_get_temp_dir(): the web server and the CLI
// have different temp directories on Windows, so the log was written by one
// process and read as empty by the other — every assertion about the request
// silently had nothing to assert against.
$logFile = __DIR__ . '/last-request.json';
$log = [
    'uri'         => $uri,
    'has_api_key_header'    => isset($h['api-key']),
    'api_key'               => $h['api-key'] ?? null,
    'has_authorization'     => isset($h['authorization']),
    'body_has_model'        => array_key_exists('model', $body),
    'body_keys'             => array_keys($body),
    'max_tokens'            => $body['max_tokens'] ?? null,
    'max_completion_tokens' => $body['max_completion_tokens'] ?? null,
    'has_temperature'       => array_key_exists('temperature', $body),
    'model'                 => $body['model'] ?? null,
    'messages'              => $body['messages'] ?? [],
];

// Append, so a retry is visible as a SECOND entry rather than overwriting the first.
$all = [];
if (is_file($logFile)) {
    $all = json_decode((string)file_get_contents($logFile), true) ?: [];
}
$all[] = $log;
file_put_contents($logFile, json_encode($all));

header('Content-Type: application/json');

// GH #171: a model's version date in the API version box. Azure answers an
// api-version it does not know with a bare 404 and these exact words.
// Checked FIRST: Azure turns an unknown address away before reading the body.
if (str_contains($uri, 'api-version=2025-08-07')) {
    http_response_code(404);
    echo json_encode(['error' => ['code' => '404', 'message' => 'Resource not found']]);
    exit;
}


// The 400 a current Azure deployment returns when it is handed `max_tokens`.
// Wording taken from Azure's own message, because our retry is triggered by
// recognising it — a paraphrase here would make the test prove nothing.
if ((str_contains($uri, '/deployments/needs-max-completion/') || str_contains($uri, '/deployments/gpt5-like/'))
    && array_key_exists('max_tokens', $body)) {
    http_response_code(400);
    echo json_encode(['error' => [
        'code'    => 'unsupported_parameter',
        'message' => "Unsupported parameter: 'max_tokens' is not supported with this model. Use 'max_completion_tokens' instead.",
    ]]);
    exit;
}

// The 400 a gpt-5-class model returns for any temperature but its default.
if ((str_contains($uri, '/deployments/needs-default-temperature/') || str_contains($uri, '/deployments/gpt5-like/'))
    && array_key_exists('temperature', $body) && $body['temperature'] != 1) {
    http_response_code(400);
    echo json_encode(['error' => [
        'code'    => 'unsupported_value',
        'message' => "Unsupported value: 'temperature' does not support 0.0 with this model. Only the default (1) value is supported.",
    ]]);
    exit;
}

// An unrelated 400 - the kind a retry must never swallow.
if (str_contains($uri, '/deployments/content-filtered/')) {
    http_response_code(400);
    echo json_encode(['error' => [
        'code'    => 'content_filter',
        'message' => "The response was filtered due to the prompt triggering Azure OpenAI's content management policy.",
    ]]);
    exit;
}

// The v1 route names no deployment in the address, so the body must.
if (str_contains($uri, '/openai/v1/chat/completions') && empty($body['model'])) {
    http_response_code(400);
    echo json_encode(['error' => ['code' => 'missing_model', 'message' => "Missing required parameter: 'model'."]]);
    exit;
}

echo json_encode([
    'id'      => 'chatcmpl-mock',
    'object'  => 'chat.completion',
    'model'   => 'gpt-4o-2024-08-06',
    'choices' => [[
        'index'         => 0,
        'message'       => ['role' => 'assistant', 'content' => 'OK'],
        'finish_reason' => 'stop',
    ]],
    'usage'   => ['prompt_tokens' => 11, 'completion_tokens' => 1, 'total_tokens' => 12],
]);
