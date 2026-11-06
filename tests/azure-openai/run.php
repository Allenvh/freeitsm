<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Azure OpenAI deployment-based endpoints (discussion #86).
 *
 *   php tests/azure-openai/run.php
 *
 * 🔴 READ THIS BEFORE TRUSTING A GREEN RUN. This suite proves that FreeITSM
 * SENDS what Azure asks for. It does NOT prove Azure accepts it, because nobody
 * on this project has an Azure subscription to point it at.
 *
 * That is a smaller gap than it sounds, and worth being precise about. Azure's
 * request body and response shape are the OpenAI ones this codebase has shipped
 * for months and which the other providers exercise daily. Exactly three things
 * are new — the URL shape, the `api-key` header, and the absence of `model` —
 * and all three are things we emit, so all three are assertable here.
 *
 * ⚠️ What this CANNOT cover, and what a real tenant is needed for: content
 * filtering (Azure returns a 400 with its own body when the filter trips),
 * api-version drift, quota and regional behaviour.
 *
 * Requires the local web server (the mock is served over HTTP, so the real cURL
 * path is exercised rather than a stubbed one).
 */

chdir(dirname(__DIR__, 2));
require_once 'config.php';
require_once 'includes/ai_provider.php';

/* ---- The mock server, started by this test -------------------------------
 * 🔑 The mock USED TO BE FETCHED FROM THE APP'S OWN WEB SERVER, at the fixed
 * URL http://localhost/freeitsm-app/tests/azure-openai/mock.php. Two things
 * were wrong with that. It only worked on a machine where the checkout happened
 * to sit at that path, so the test was unrunnable for anyone else; and it
 * required tests/ to be SERVED, which is exactly what the CLI guard at the top
 * of every file here now prevents, for good reason.
 *
 * So the test starts its own `php -S` on a free port, serving only this
 * directory, and stops it again at the end. Nothing outside this process can
 * reach it, and the real cURL path is still exercised rather than stubbed —
 * which was the point of using HTTP in the first place. */
$devNull = DIRECTORY_SEPARATOR === '\\' ? 'NUL' : '/dev/null';

/** A port nothing is listening on, so two runs (or a busy machine) cannot clash. */
function freePort(): int {
    $sock = @stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
    if (!$sock) { fwrite(STDERR, "could not find a free port: $errstr\n"); exit(1); }
    $name = stream_socket_get_name($sock, false);
    fclose($sock);
    return (int)substr($name, strrpos($name, ':') + 1);
}

/* 🔴 bypass_shell IS NOT OPTIONAL ON WINDOWS. Without it proc_open runs the
   command through `cmd /c`, so the direct child is cmd.exe and php.exe is a
   GRANDchild. proc_terminate() then kills the wrapper and leaves the server
   running, holding its port and the inherited console handles — the first
   version of this left two orphaned servers behind and hung the terminal that
   started them. With it, php.exe is the child we started and the one we stop. */
$port = freePort();
$mockProc = proc_open(
    escapeshellarg(PHP_BINARY) . ' -S 127.0.0.1:' . $port . ' -t ' . escapeshellarg(__DIR__),
    [0 => ['file', $devNull, 'r'], 1 => ['file', $devNull, 'w'], 2 => ['file', $devNull, 'w']],
    $pipes,
    null,
    null,
    ['bypass_shell' => true]
);
if (!is_resource($mockProc)) { fwrite(STDERR, "could not start the mock server\n"); exit(1); }

/* Stopped however this script ends, including on a fatal — an orphaned server
   holding a port is a worse legacy than a failed test. */
register_shutdown_function(function () use (&$mockProc) {
    if (is_resource($mockProc)) proc_terminate($mockProc);
});

// It is not ready the instant proc_open returns; wait for the port to answer.
$ready = false;
for ($i = 0; $i < 100 && !$ready; $i++) {
    $c = @fsockopen('127.0.0.1', $port, $e1, $e2, 0.2);
    if ($c) { $ready = true; fclose($c); } else { usleep(100000); }
}
if (!$ready) { fwrite(STDERR, "the mock server never came up on port $port\n"); exit(1); }

$MOCK = "http://127.0.0.1:$port/mock.php";
$LOG  = __DIR__ . '/last-request.json';   // see the note in mock.php

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  ok    $what\n"; }
    else       { $fail++; echo "  FAIL  $what" . ($detail ? "  ($detail)" : '') . "\n"; }
}
function section(string $t): void { echo "\n$t\n" . str_repeat('-', strlen($t)) . "\n"; }
function sent(): array {
    global $LOG;
    return is_file($LOG) ? (json_decode((string)file_get_contents($LOG), true) ?: []) : [];
}
function reset_log(): void { global $LOG; @unlink($LOG); }

// ── 1. The URL we build ─────────────────────────────────────────────────────
section('1. The URL — the part that cannot be expressed as a base URL');

$url = aiAzureChatUrl([
    'azure_endpoint'    => 'https://company.openai.azure.com/',
    'azure_deployment'  => 'gpt-4o',
    'azure_api_version' => '2024-02-01',
]);
ok('matches Azure\'s documented shape',
    $url === 'https://company.openai.azure.com/openai/deployments/gpt-4o/chat/completions?api-version=2024-02-01',
    $url);

// The three ways an administrator will paste the endpoint, all meaning the same.
foreach ([
    'https://company.openai.azure.com'         => 'no trailing slash',
    'https://company.openai.azure.com/'        => 'trailing slash',
    'https://company.openai.azure.com/openai'  => 'with /openai already on it',
    'https://company.openai.azure.com/openai/' => 'both',
] as $endpoint => $label) {
    $u = aiAzureChatUrl(['azure_endpoint' => $endpoint, 'azure_deployment' => 'gpt-4o', 'azure_api_version' => '2024-02-01']);
    ok("normalises an endpoint pasted $label", substr_count($u, '/openai/') === 1
        && str_contains($u, '.com/openai/deployments/gpt-4o/'), $u);
}

// GH #171: the portal's whole "Target URI", or a Foundry project address, pasted
// into the endpoint box. Everything after the resource is ours to build.
$u = aiAzureChatUrl(['azure_endpoint' => 'https://x.cognitiveservices.azure.com/openai/deployments/gpt-5-nano/chat/completions?api-version=2025-01-01-preview',
                     'azure_deployment' => 'gpt-5-nano', 'azure_api_version' => '2025-04-01-preview']);
ok('a pasted Target URI is cut back to the resource',
    $u === 'https://x.cognitiveservices.azure.com/openai/deployments/gpt-5-nano/chat/completions?api-version=2025-04-01-preview', $u);
$u = aiAzureChatUrl(['azure_endpoint' => 'https://x.services.ai.azure.com/api/projects/myproj',
                     'azure_deployment' => 'd', 'azure_api_version' => '2024-10-21']);
ok('a pasted Foundry project address is cut back to the resource',
    $u === 'https://x.services.ai.azure.com/openai/deployments/d/chat/completions?api-version=2024-10-21', $u);
$u = aiAzureChatUrl(['azure_endpoint' => 'https://x.openai.azure.com/', 'azure_deployment' => 'd', 'azure_api_version' => 'v1']);
ok('API version "v1" uses the v1 route, with no query string',
    $u === 'https://x.openai.azure.com/openai/v1/chat/completions', $u);

// The explanation the Test button gives for #171's exact failure.
$why = aiExplainFailure(['provider' => 'azure', 'azure_api_version' => '2025-08-07'], 'Resource not found (HTTP 404)');
ok('a model version date in the API version box is named as the cause of a 404',
    stripos($why, 'MODEL\'s version') !== false, $why);
$why = aiExplainFailure(['provider' => 'azure', 'azure_api_version' => '2024-10-21', 'azure_deployment' => 'd'],
    'The API deployment for this resource does not exist. (HTTP 404)');
ok('CONTROL - a missing deployment is named as the deployment, not the API version',
    stripos($why, 'no deployment called "d"') !== false, $why);

ok('falls back to a default api-version when none is given',
    str_contains(aiAzureChatUrl(['azure_endpoint' => 'https://x.openai.azure.com', 'azure_deployment' => 'd']),
           'api-version=' . AI_AZURE_DEFAULT_API_VERSION));

// A deployment name is chosen by the customer and can contain anything a name
// can. Encoding it is not decoration — an unencoded space breaks the request line.
ok('escapes a deployment name with a space in it',
    str_contains(aiAzureChatUrl(['azure_endpoint' => 'https://x.openai.azure.com', 'azure_deployment' => 'my gpt', 'azure_api_version' => 'v']),
           '/deployments/my%20gpt/'));

// ── 2. Configuration errors name the RIGHT field ────────────────────────────
section('2. A missing field is named as the field the administrator can see');

$err = '';
try { aiProviderChat(['provider' => 'azure', 'api_key' => 'k', 'azure_deployment' => 'd'], []); }
catch (RuntimeException $e) { $err = $e->getMessage(); }
ok('missing endpoint says "endpoint", not "model"',
    stripos($err, 'endpoint') !== false && stripos($err, 'model') === false, $err);

$err = '';
try { aiProviderChat(['provider' => 'azure', 'api_key' => 'k', 'azure_endpoint' => 'https://x/'], []); }
catch (RuntimeException $e) { $err = $e->getMessage(); }
ok('missing deployment says "deployment"', stripos($err, 'deployment') !== false, $err);

// CONTROL — the model check still fires for the providers that DO need one, so
// the branch above has not simply disabled it for everybody.
$err = '';
try { aiProviderChat(['provider' => 'openai', 'api_key' => 'k', 'model' => ''], []); }
catch (RuntimeException $e) { $err = $e->getMessage(); }
ok('CONTROL — openai with no model still says "No model configured"',
    stripos($err, 'model') !== false, $err);

// ── 3. What actually goes down the wire ─────────────────────────────────────
section('3. 🔴 The bytes we send (against the local stand-in)');

reset_log();
$cfg = [
    'provider'          => 'azure',
    'api_key'           => 'azure-test-key-123',
    'verify_ssl'        => false,
    'azure_endpoint'    => $MOCK . '?x=',
    'azure_deployment'  => 'ok',
    'azure_api_version' => '2024-02-01',
];
// The mock is one script rather than a directory tree, so the "endpoint" above
// carries a query string of its own and the path lands in it. What matters for
// these assertions is the headers and the body, which are unaffected.
$cfg['azure_endpoint'] = $MOCK . '?p=';

$result = aiProviderChat($cfg, ['system' => 'sys', 'user' => 'hello', 'max_tokens' => 16]);
$log = sent();

ok('the call succeeded and returned the content', ($result['content'] ?? '') === 'OK');
ok('token counts are read from Azure\'s (OpenAI-shaped) usage block',
    $result['tokens_in'] === 11 && $result['tokens_out'] === 1);
ok('the deployment is reported back as the model', ($result['model'] ?? '') === 'ok');
ok('the provider is reported as azure', ($result['provider'] ?? '') === 'azure');

ok('🔴 the key goes in an api-key header', !empty($log[0]['has_api_key_header']));
ok('🔴 …carrying the actual key', ($log[0]['api_key'] ?? '') === 'azure-test-key-123');
ok('🔴 and NOT in Authorization: Bearer', empty($log[0]['has_authorization']));
ok('🔴 no `model` field is sent — the deployment already decided it',
    isset($log[0]['body_has_model']) && $log[0]['body_has_model'] === false,
    implode(',', $log[0]['body_keys'] ?? []));
ok('the messages are the ordinary system + user pair',
    count($log[0]['messages'] ?? []) === 2
    && $log[0]['messages'][0]['role'] === 'system'
    && $log[0]['messages'][1]['content'] === 'hello');

// CONTROL — the OpenAI path must be UNCHANGED by the refactor that made the URL
// and auth header parameters. Same mock, provider openai.
reset_log();
$result = aiProviderChat([
    'provider' => 'openai', 'api_key' => 'sk-control', 'model' => 'gpt-4o',
    'verify_ssl' => false, 'base_url' => $MOCK . '?p=',
], ['system' => 'sys', 'user' => 'hello', 'max_tokens' => 16]);
$log = sent();
ok('CONTROL — openai still sends Authorization: Bearer', !empty($log[0]['has_authorization']));
ok('CONTROL — openai still sends a model field', !empty($log[0]['body_has_model']));
ok('CONTROL — openai does NOT send api-key', empty($log[0]['has_api_key_header']));

// ── 4. The max_tokens / max_completion_tokens retry ─────────────────────────
section('4. Newer deployments: max_tokens → max_completion_tokens');

reset_log();
$cfg['azure_deployment'] = 'needs-max-completion';
$result = aiProviderChat($cfg, ['system' => 'sys', 'user' => 'hello', 'max_tokens' => 16]);
$log = sent();

ok('the call still succeeds', ($result['content'] ?? '') === 'OK');
ok('it took exactly TWO attempts, not a loop', count($log) === 2, 'attempts: ' . count($log));
ok('the first attempt used max_tokens', ($log[0]['max_tokens'] ?? null) === 16);
ok('🔴 the retry swapped in max_completion_tokens',
    ($log[1]['max_completion_tokens'] ?? null) === 16 && ($log[1]['max_tokens'] ?? null) === null);

// 🔴 CONTROL — the retry must fire ONLY on that specific refusal. An unrelated
// 400 (a bad key, a content filter) has to surface, not be silently retried.
// (This used to grep the source for the old retry line, which proved nothing
// about behaviour and broke when the retry moved into aiOpenAICompatPost.)
reset_log();
$threw = '';
try {
    aiProviderChat(array_merge($cfg, ['azure_deployment' => 'content-filtered']), [
        'system' => 'sys', 'user' => 'hello', 'max_tokens' => 16,
    ]);
} catch (RuntimeException $e) { $threw = $e->getMessage(); }
ok('CONTROL — an unrelated 400 surfaces as an error', stripos($threw, 'content management policy') !== false, $threw);
ok('CONTROL — …after exactly ONE attempt, not a retry', count(sent()) === 1, 'attempts: ' . count(sent()));

// GH #171: gpt-5-class models refuse any temperature but their default.
section('4b. Newer models: temperature left at the model\'s default');

reset_log();
$result = aiProviderChat(array_merge($cfg, ['azure_deployment' => 'needs-default-temperature']),
    ['system' => 'sys', 'user' => 'hello', 'max_tokens' => 16]);
$log = sent();
ok('the call still succeeds', ($result['content'] ?? '') === 'OK');
ok('two attempts: the first sent temperature, the second did not',
    count($log) === 2 && !empty($log[0]['has_temperature']) && empty($log[1]['has_temperature']), 'attempts: ' . count($log));

reset_log();
$result = aiProviderChat(array_merge($cfg, ['azure_deployment' => 'gpt5-like']),
    ['system' => 'sys', 'user' => 'hello', 'max_tokens' => 16]);
$log = sent();
ok('🔴 a model refusing BOTH is put right in three attempts', ($result['content'] ?? '') === 'OK' && count($log) === 3, 'attempts: ' . count($log));
ok('…ending with max_completion_tokens and no temperature',
    ($log[2]['max_completion_tokens'] ?? null) === 16 && empty($log[2]['has_temperature']));

// Tool calling shares the fix: one round, so the same three attempts.
reset_log();
$result = aiProviderChatTools(array_merge($cfg, ['azure_deployment' => 'gpt5-like']),
    ['system' => 'sys', 'user' => 'hello', 'max_tokens' => 16], [], function () { return ''; });
ok('tool calling gets the same fixes', ($result['content'] ?? '') === 'OK' && count(sent()) === 3, 'attempts: ' . count(sent()));

// GH #171 end to end: the 404, then the reading the Test button shows for it.
reset_log();
$threw = '';
$bad = array_merge($cfg, ['azure_deployment' => 'gpt5-like', 'azure_api_version' => '2025-08-07']);
try { aiProviderChat($bad, ['system' => 'sys', 'user' => 'hello', 'max_tokens' => 16]); }
catch (RuntimeException $e) { $threw = $e->getMessage(); }
ok('#171: a model version as the API version fails with Azure\'s 404', $threw === 'Resource not found (HTTP 404)', $threw);
ok('#171: …and the reading names the API version box', stripos(aiExplainFailure($bad, $threw), 'API version') !== false);
ok('#171: …after ONE attempt (a 404 is not retried)', count(sent()) === 1, 'attempts: ' . count(sent()));

// The v1 route: no deployment in the address, so it travels as `model`.
reset_log();
$result = aiProviderChat(array_merge($cfg, ['azure_api_version' => 'v1', 'azure_deployment' => 'my-deploy']),
    ['system' => 'sys', 'user' => 'hello', 'max_tokens' => 16]);
$log = sent();
ok('v1: the call succeeds', ($result['content'] ?? '') === 'OK');
ok('v1: the deployment is sent as the model', ($log[0]['model'] ?? '') === 'my-deploy', (string)($log[0]['model'] ?? 'none'));
ok('v1: still the api-key header', !empty($log[0]['has_api_key_header']) && empty($log[0]['has_authorization']));

// ── 5. The registry ─────────────────────────────────────────────────────────
section('5. Wiring');

ok('azure is an accepted provider', AiProvider::tryFrom('azure') === AiProvider::Azure);
require_once 'includes/ai_settings.php';
$keys = aiSettingsKeys('tickets_reply_cleanup');
foreach (['azure_endpoint', 'azure_deployment', 'azure_api_version'] as $f) {
    ok("a settings key exists for $f", isset($keys[$f]) && str_starts_with($keys[$f], 'tickets_reply_cleanup_'));
}

reset_log();
echo "\n" . str_repeat('=', 62) . "\n";
echo "$pass passed, $fail failed\n";
echo "⚠️  Green here means WE SEND THE RIGHT REQUEST. It does not mean Azure\n";
echo "    accepts it — that needs a real tenant. See the header of this file.\n";
exit($fail > 0 ? 1 : 0);
