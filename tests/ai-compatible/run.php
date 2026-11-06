<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Custom OpenAI-compatible endpoints - Ollama, LM Studio, vLLM (GH #192).
 *
 *   php tests/ai-compatible/run.php
 *
 * Proves what FreeITSM SENDS to a server the administrator names, and how it
 * reads each kind of answer, against a local stand-in shaped on Ollama's /v1 API
 * (mock.php). It does not prove a particular model behaves - only a real server
 * can - but every request leaves this process for the stand-in and nowhere else,
 * which is the property the issue cares most about: no cloud fallback.
 *
 * Section 5 saves and reloads a real feature's AI settings, so it needs the
 * database. 🔴 It snapshots EVERY settings row of that feature first and puts
 * them back exactly - including deleting rows that did not exist - however the
 * script ends.
 */

chdir(dirname(__DIR__, 2));
require_once 'config.php';
require_once 'includes/functions.php';
require_once 'includes/ai_settings.php';

$devNull = DIRECTORY_SEPARATOR === '\\' ? 'NUL' : '/dev/null';

/** A port nothing is listening on. */
function freePort(): int {
    $sock = @stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
    if (!$sock) { fwrite(STDERR, "could not find a free port: $errstr\n"); exit(1); }
    $name = stream_socket_get_name($sock, false);
    fclose($sock);
    return (int)substr($name, strrpos($name, ':') + 1);
}

// The stand-in serves as a ROUTER script, so it answers on any path - including
// the wrong ones a mistyped base URL produces. bypass_shell: see tests/azure-openai/run.php.
$port = freePort();
$mockProc = proc_open(
    escapeshellarg(PHP_BINARY) . ' -S 127.0.0.1:' . $port . ' ' . escapeshellarg(__DIR__ . DIRECTORY_SEPARATOR . 'mock.php'),
    [0 => ['file', $devNull, 'r'], 1 => ['file', $devNull, 'w'], 2 => ['file', $devNull, 'w']],
    $pipes, null, null, ['bypass_shell' => true]
);
if (!is_resource($mockProc)) { fwrite(STDERR, "could not start the mock server\n"); exit(1); }
register_shutdown_function(function () use (&$mockProc) { if (is_resource($mockProc)) proc_terminate($mockProc); });

$ready = false;
for ($i = 0; $i < 100 && !$ready; $i++) {
    $c = @fsockopen('127.0.0.1', $port, $e1, $e2, 0.2);
    if ($c) { $ready = true; fclose($c); } else { usleep(100000); }
}
if (!$ready) { fwrite(STDERR, "the mock server never came up on port $port\n"); exit(1); }

$BASE = "http://127.0.0.1:$port/v1";
$LOG  = __DIR__ . '/last-request.json';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  ok    $what\n"; }
    else       { $fail++; echo "  FAIL  $what" . ($detail ? "  ($detail)" : '') . "\n"; }
}
function section(string $t): void { echo "\n$t\n" . str_repeat('-', strlen($t)) . "\n"; }
function sent(): array { global $LOG; return is_file($LOG) ? (json_decode((string)file_get_contents($LOG), true) ?: []) : []; }
function reset_log(): void { global $LOG; @unlink($LOG); }
function cfg(string $model, string $base, string $key = ''): array {
    return ['provider' => 'openai_compatible', 'model' => $model, 'api_key' => $key,
            'compatible_base_url' => $base, 'verify_ssl' => true, 'reasoning' => false];
}
/** Every URL a call reached, from the provider's own trace. */
function tracedUrls(): array { return array_map(function ($t) { return $t['url']; }, aiProviderTraceTake()); }
/** Runs a call that should fail; returns [message, every URL it tried]. */
function failing(callable $fn): array {
    aiProviderTraceStart();
    $msg = '';
    try { $fn(); } catch (Throwable $e) { $msg = $e->getMessage(); }
    return [$msg, tracedUrls()];
}
function onlyStandIn(array $urls): bool {
    global $port;
    foreach ($urls as $u) if (!str_starts_with($u, "http://127.0.0.1:$port/")) return false;
    return true;
}
$say = ['system' => 'Test', 'user' => 'Say OK', 'max_tokens' => 16];

// ── 1. The address ──────────────────────────────────────────────────────────
section('1. The address the administrator gives');
foreach (['http://h:11434/v1' => 'as documented', 'http://h:11434/v1/' => 'with a trailing slash',
          'http://h:11434/v1/chat/completions' => 'with the whole path pasted'] as $b => $label) {
    $u = aiCompatibleChatUrl(['compatible_base_url' => $b]);
    ok("a base URL $label becomes the chat address", $u === 'http://h:11434/v1/chat/completions', $u);
}
ok('http is accepted', aiCompatibleBaseUrlValid('http://llm-host:11434/v1'));
ok('https is accepted', aiCompatibleBaseUrlValid('https://llm.example.internal/v1'));
foreach (['file:///etc/passwd', 'gopher://h:70/x', 'llm-host:11434/v1', 'ftp://h/v1', ''] as $bad) {
    ok("\"$bad\" is refused", !aiCompatibleBaseUrlValid($bad));
}

// ── 2. "Is it set up?" ──────────────────────────────────────────────────────
section('2. Set up without a key');
ok('a custom endpoint with an address and a model is set up, with no key', aiConfigured(cfg('ok', $BASE)));
ok('...but not without the address', !aiConfigured(cfg('ok', '')));
ok('...or without the model', !aiConfigured(cfg('', $BASE)));
ok('OpenAI with no key is still NOT set up (control)', !aiConfigured(['provider' => 'openai', 'model' => 'gpt-4o', 'api_key' => '']));
ok('OpenAI with a key is set up (control)', aiConfigured(['provider' => 'openai', 'model' => 'gpt-4o', 'api_key' => 'sk-x']));

// ── 3. Ordinary chat ────────────────────────────────────────────────────────
section('3. Ordinary chat');
reset_log(); aiProviderTraceStart();
$r = aiProviderChat(cfg('ok', $BASE), $say);
$urls = tracedUrls(); $log = sent();
ok('answers', ($r['content'] ?? '') === 'OK', json_encode($r));
ok('goes to {base}/chat/completions', ($log[0]['uri'] ?? '') === '/v1/chat/completions', (string)($log[0]['uri'] ?? ''));
ok('sends the model', ($log[0]['model'] ?? '') === 'ok');
ok('with no key, sends NO Authorization header (no dummy credential)', ($log[0]['authorization'] ?? null) === null);
ok('reports the provider and model', $r['provider'] === 'openai_compatible' && $r['model'] === 'ok');
ok('nothing else was contacted', count($urls) === 1 && onlyStandIn($urls), implode(', ', $urls));

reset_log();
$r = aiProviderChat(cfg('needs-key', $BASE, 'local-secret'), $say);
$log = sent();
ok('with a key, sends it as a Bearer token', ($log[0]['authorization'] ?? '') === 'Bearer local-secret');
ok('...and a server that needs one answers', ($r['content'] ?? '') === 'OK');

// ── 4. Tool calling ─────────────────────────────────────────────────────────
section('4. Tool calling');
$ping = [['name' => 'ping', 'description' => 'pong', 'schema' => ['type' => 'object', 'properties' => (object)[]]]];
reset_log(); aiProviderTraceStart();
$r = aiProviderChatTools(cfg('tools-ok', $BASE), ['system' => 'Test', 'user' => 'Ping', 'max_rounds' => 2, 'max_tokens' => 64],
    $ping, function () { return 'pong'; });
$urls = tracedUrls(); $log = sent();
ok('the model\'s tool call is run', count($r['calls']) === 1 && $r['calls'][0]['name'] === 'ping');
ok('...and its answer read', $r['content'] === 'OK', $r['content']);
ok('both rounds went to the custom address, never to api.openai.com', count($urls) === 2 && onlyStandIn($urls), implode(', ', $urls));
ok('without a key there either', ($log[0]['authorization'] ?? null) === null && ($log[1]['authorization'] ?? null) === null);
ok('the result goes back as a tool message', in_array('tool', $log[1]['roles'] ?? [], true));

[$msg, $urls] = failing(function () use ($ping, $BASE) {
    aiProviderChatTools(cfg('no-tools', $BASE), ['user' => 'Ping', 'max_rounds' => 2], $ping, function () { return 'pong'; });
});
ok('a model without tool support fails, with the server\'s own words', str_contains($msg, 'does not support tools'), $msg);
ok('...explained as tool calling', stripos(aiExplainFailure(cfg('no-tools', $BASE), $msg), 'tool calling') !== false);
ok('...and not retried anywhere else', count($urls) === 1 && onlyStandIn($urls), implode(', ', $urls));

// ── 5. Failures name the cause, and never fall back ─────────────────────────
section('5. Failures');
[$msg, $urls] = failing(function () use ($say, $BASE) { aiProviderChat(cfg('missing', $BASE), $say); });
ok('a model that is not on the server: 404 with its words', str_contains($msg, 'not found') && str_contains($msg, '(HTTP 404)'), $msg);
ok('...explained as the model name', stripos(aiExplainFailure(cfg('missing', $BASE), $msg), 'does not have a model called "missing"') !== false);
ok('...no fallback', onlyStandIn($urls));

$noV1 = "http://127.0.0.1:$port";
[$msg, $urls] = failing(function () use ($say, $noV1) { aiProviderChat(cfg('ok', $noV1), $say); });
ok('a base URL without /v1: 404', str_contains($msg, '(HTTP 404)'), $msg);
ok('...explained as the address, naming /v1', str_contains(aiExplainFailure(cfg('ok', $noV1), $msg), '/v1'));
ok('...no fallback', onlyStandIn($urls));

[$msg, $urls] = failing(function () use ($say, $BASE) { aiProviderChat(cfg('not-chat', $BASE), $say); });
ok('a 200 that is not a chat completion is a failure, not an empty answer', str_contains($msg, 'not with a chat completion'), $msg);
ok('...explained as not an OpenAI-compatible API', stripos(aiExplainFailure(cfg('not-chat', $BASE), $msg), 'not an OpenAI-compatible API') !== false);
ok('...and not retried (one request)', count($urls) === 1, (string)count($urls));

[$msg, $urls] = failing(function () use ($say, $BASE) { aiProviderChat(cfg('needs-key', $BASE), $say); });
ok('a server that needs a key, sent none: 401', str_contains($msg, '(HTTP 401)'), $msg);
ok('...explained as a key', stripos(aiExplainFailure(cfg('needs-key', $BASE), $msg), 'API key') !== false);

$dead = 'http://localhost:' . freePort() . '/v1';
echo "  (a refused connection is retried, as every provider's is - a few seconds)\n";
[$msg, $urls] = failing(function () use ($say, $dead) { aiProviderChat(cfg('ok', $dead), $say); });
ok('nothing listening: a network error', stripos($msg, 'Network error') !== false, $msg);
$hint = aiExplainFailure(cfg('ok', $dead), $msg);
ok('...explained: localhost is the FreeITSM server, in Docker the container', stripos($hint, 'Docker') !== false && stripos($hint, 'FreeITSM server') !== false, $hint);
ok('...every attempt went to that address, none anywhere else', $urls && count(array_filter($urls, function ($u) use ($dead) { return !str_starts_with($u, $dead); })) === 0, implode(', ', $urls));

$e = '';
try { aiProviderChat(cfg('ok', ''), $say); } catch (Throwable $x) { $e = $x->getMessage(); }
ok('no base URL: says so, rather than "no API key"', $e === 'No base URL configured.', $e);

// ── 6. Saving and loading (a real feature's settings, restored after) ────────
section('6. Settings - save, reload, and the key that must not follow');
$ns   = 'lms_ai';
$conn = connectToDatabase();
$keys = aiSettingsKeys($ns);
$place = implode(',', array_fill(0, count($keys), '?'));
$st = $conn->prepare("SELECT setting_key, setting_value FROM system_settings WHERE setting_key IN ($place)");
$st->execute(array_values($keys));
$snapshot = $st->fetchAll(PDO::FETCH_KEY_PAIR);
register_shutdown_function(function () use ($conn, $keys, $place, $snapshot) {
    $conn->prepare("DELETE FROM system_settings WHERE setting_key IN ($place)")->execute(array_values($keys));
    $ins = $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)");
    foreach ($snapshot as $k => $v) $ins->execute([$k, $v]);
});

aiSettingsSave($conn, $ns, ['provider' => 'openai', 'model' => 'gpt-4o', 'api_key' => 'sk-cloud-secret']);
aiSettingsSave($conn, $ns, ['provider' => 'openai_compatible', 'model' => 'llama3.1:8b', 'api_key' => '',
                            'compatible_base_url' => 'http://llm-host:11434/v1']);
$cfg = aiSettingsLoad($conn, $ns);
ok('reloads as a custom endpoint', $cfg['provider'] === 'openai_compatible' && $cfg['model'] === 'llama3.1:8b');
ok('reloads the base URL', $cfg['compatible_base_url'] === 'http://llm-host:11434/v1');
ok('🔴 the OpenAI key was NOT carried to the custom address', $cfg['api_key'] === '');
ok('set up without a key', aiConfigured($cfg));
ok('the UI view returns the address in the clear and no key', aiSettingsForUi($conn, $ns)['compatible_base_url'] === 'http://llm-host:11434/v1'
    && aiSettingsForUi($conn, $ns)['has_key'] === false);
ok('the loaded settings have NO `base_url` (which would redirect OpenAI)', !array_key_exists('base_url', $cfg));

aiSettingsSave($conn, $ns, ['provider' => 'openai_compatible', 'model' => 'llama3.1:8b', 'api_key' => 'local-key',
                            'compatible_base_url' => 'http://llm-host:11434/v1']);
ok('a key can be saved for a custom endpoint', aiSettingsLoad($conn, $ns)['api_key'] === 'local-key');
aiSettingsSave($conn, $ns, ['provider' => 'openai_compatible', 'model' => 'llama3.1:8b', 'api_key' => '',
                            'compatible_base_url' => 'http://llm-host:11434/v1']);
ok('...kept when the box is left blank', aiSettingsLoad($conn, $ns)['api_key'] === 'local-key');
aiSettingsSave($conn, $ns, ['provider' => 'openai_compatible', 'model' => 'llama3.1:8b', 'api_key' => '',
                            'compatible_base_url' => 'http://llm-host:11434/v1', 'clear_api_key' => true]);
ok('...and removed when asked', aiSettingsLoad($conn, $ns)['api_key'] === '');

aiSettingsSave($conn, $ns, ['provider' => 'openai_compatible', 'model' => 'llama3.1:8b', 'api_key' => 'local-key',
                            'compatible_base_url' => 'http://llm-host:11434/v1']);
aiSettingsSave($conn, $ns, ['provider' => 'openai', 'model' => 'gpt-4o', 'api_key' => '', 'compatible_base_url' => 'http://llm-host:11434/v1']);
$cfg = aiSettingsLoad($conn, $ns);
ok('🔴 switching back, the local key is NOT sent to OpenAI', $cfg['api_key'] === '');
ok('...and the address is kept for next time', $cfg['compatible_base_url'] === 'http://llm-host:11434/v1');

$before = aiSettingsLoad($conn, $ns);
$e = '';
try {
    aiSettingsSave($conn, $ns, ['provider' => 'openai_compatible', 'model' => 'x', 'compatible_base_url' => 'file:///etc/passwd']);
} catch (InvalidArgumentException $x) { $e = $x->getMessage(); }
ok('a file:// base URL is refused when saving', $e !== '', $e);
ok('...and nothing about the provider was changed', aiSettingsLoad($conn, $ns)['provider'] === $before['provider']);
$e = '';
try {
    aiSettingsSave($conn, $ns, ['provider' => 'openai_compatible', 'model' => '', 'compatible_base_url' => 'http://h/v1']);
} catch (InvalidArgumentException $x) { $e = $x->getMessage(); }
ok('a custom endpoint with no model is refused', $e !== '', $e);

aiSettingsSave($conn, $ns, ['provider' => 'anthropic', 'model' => 'claude-haiku-4-5-20251001', 'api_key' => 'sk-ant-x']);
aiSettingsSave($conn, $ns, ['provider' => 'openai', 'model' => 'gpt-4o', 'api_key' => '']);
ok('control: Anthropic to OpenAI keeps the key as before (only the custom boundary drops it)', aiSettingsLoad($conn, $ns)['api_key'] === 'sk-ant-x');

reset_log();
echo "\n" . str_repeat('=', 62) . "\n";
echo "$pass passed, $fail failed\n";
echo "⚠️  Green means FreeITSM sends the right request and reads each answer\n";
echo "    correctly. A real model server is the only test of a real model.\n";
exit($fail > 0 ? 1 : 0);
