<?php
/* 🔴 NEVER OVER THE WEB. See tests/web-exposure-guard.php. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * The AiProvider enum (4.0) sends what the old if/elseif chains sent.
 *
 *   php tests/ai-provider-enum.php
 *
 * Until 4.0 includes/ai_provider.php had a list of provider names, an in_array()
 * check, and a three-way branch in each of its two call paths. Those became the
 * AiProvider enum: four providers that speak the OpenAI wire format now share one
 * path and ask the case for their address and headers. tests/azure-openai and
 * tests/ai-compatible cover Azure and a custom endpoint; this covers the two they
 * do not - OpenAI and OpenRouter - by pointing them at the same local stand-in
 * (tests/ai-compatible/mock.php) and reading back exactly what arrived.
 *
 * No database, no network: every request goes to 127.0.0.1.
 */

chdir(dirname(__DIR__));
require_once 'config.php';                  // the SSL policy every outbound call goes through
require_once 'includes/ai_provider.php';
$devNull = DIRECTORY_SEPARATOR === '\\' ? 'NUL' : '/dev/null';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  ok    $what\n"; }
    else       { $fail++; echo "  FAIL  $what" . ($detail ? "  ($detail)" : '') . "\n"; }
}

echo "AiProvider enum (4.0)\n" . str_repeat('=', 60) . "\n";

// ---------------------------------------------------------------- the enum
echo "\nThe enum\n";
ok('the five stored names are the five cases', array_map(fn($c) => $c->value, AiProvider::cases())
   === ['anthropic', 'openai', 'openrouter', 'azure', 'openai_compatible']);
ok('no provider saved means Anthropic, as before', AiProvider::fromConfig([]) === AiProvider::Anthropic);
$threw = ''; try { AiProvider::fromConfig(['provider' => 'chatgpt']); } catch (RuntimeException $e) { $threw = $e->getMessage(); }
ok('an unknown provider throws, with the old message', $threw === 'Unknown AI provider: chatgpt', $threw);
ok('only a custom endpoint may have no key', !AiProvider::OpenAICompatible->needsApiKey() && AiProvider::OpenAI->needsApiKey() && AiProvider::Azure->needsApiKey());
ok('labels', AiProvider::Azure->label() === 'Azure OpenAI' && AiProvider::OpenAICompatible->label() === 'Custom endpoint');
ok('OpenAI default address', AiProvider::OpenAI->chatUrl([]) === 'https://api.openai.com/v1/chat/completions');
ok('OpenRouter default address', AiProvider::OpenRouter->chatUrl([]) === 'https://openrouter.ai/api/v1/chat/completions');
ok('a base_url override is honoured, trailing slash or not', AiProvider::OpenAI->chatUrl(['base_url' => 'http://x/v1/']) === 'http://x/v1/chat/completions');
ok('Azure sends api-key, not Bearer', AiProvider::Azure->authHeaders('K') === ['api-key: K']);
ok('a custom endpoint with no key sends no auth header', AiProvider::OpenAICompatible->authHeaders('') === []);
ok('only OpenRouter adds attribution headers', count(AiProvider::OpenRouter->extraHeaders([])) === 2 && AiProvider::OpenAI->extraHeaders([]) === []);

// ---------------------------------------------------------------- over the wire
$sock = stream_socket_server('tcp://127.0.0.1:0');
$port = (int)substr(stream_socket_get_name($sock, false), strrpos(stream_socket_get_name($sock, false), ':') + 1);
fclose($sock);
$mock = proc_open(escapeshellarg(PHP_BINARY) . ' -S 127.0.0.1:' . $port . ' ' . escapeshellarg('tests' . DIRECTORY_SEPARATOR . 'ai-compatible' . DIRECTORY_SEPARATOR . 'mock.php'),
    [0 => ['file', $devNull, 'r'], 1 => ['file', $devNull, 'w'], 2 => ['file', $devNull, 'w']], $pipes, null, null, ['bypass_shell' => true]);
register_shutdown_function(function () use (&$mock) { if (is_resource($mock)) proc_terminate($mock); });
for ($i = 0, $up = false; $i < 100 && !$up; $i++) { $c = @fsockopen('127.0.0.1', $port, $e1, $e2, 0.2); if ($c) { $up = true; fclose($c); } else usleep(100000); }
$LOG = 'tests/ai-compatible/last-request.json';
$base = "http://127.0.0.1:$port/v1";
$say = ['system' => 'Test', 'user' => 'Say OK', 'max_tokens' => 16];
$last = function () use ($LOG) { $all = is_file($LOG) ? (json_decode((string)file_get_contents($LOG), true) ?: []) : []; return end($all) ?: []; };

foreach (['openai' => false, 'openrouter' => true] as $name => $attributed) {
    echo "\n$name, over the wire\n";
    @unlink($LOG);
    $cfg = ['provider' => $name, 'model' => 'ok', 'api_key' => 'sk-test', 'base_url' => $base, 'verify_ssl' => true, 'reasoning' => false];
    $r = null; $err = '';
    try { $r = aiProviderChat($cfg, $say + ['title' => 'ZZ title']); } catch (Throwable $e) { $err = $e->getMessage(); }
    $s = $last();
    ok("$name: a chat reaches the address it was given", ($s['uri'] ?? '') === '/v1/chat/completions', $err ?: json_encode($s));
    ok("$name: the key goes as a Bearer token", ($s['authorization'] ?? '') === 'Bearer sk-test');
    ok("$name: the model is sent", ($s['model'] ?? '') === 'ok');
    ok("$name: the answer is read, and reports the provider by its stored name", $r && ($r['provider'] ?? '') === $name && trim((string)$r['content']) !== '', $err);
    ok($attributed ? "$name: attribution headers sent, with the caller's title" : "$name: no attribution headers",
       $attributed ? (($s['referer'] ?? '') === 'https://freeitsm.co.uk' && ($s['title'] ?? '') === 'ZZ title')
                   : ($s['referer'] ?? null) === null && ($s['title'] ?? null) === null, json_encode($s));

    @unlink($LOG);
    $err = '';
    try { aiProviderChatTools(['model' => 'tools-ok'] + $cfg, $say, [['name' => 'ping', 'description' => 'p', 'schema' => ['type' => 'object', 'properties' => new stdClass()]]], fn() => 'pong'); }
    catch (Throwable $e) { $err = $e->getMessage(); }
    $s = $last();
    ok("$name: the tool-calling path sends the same address and key, with the tools",
       ($s['uri'] ?? '') === '/v1/chat/completions' && ($s['authorization'] ?? '') === 'Bearer sk-test' && !empty($s['has_tools']),
       $err ?: json_encode($s));
    ok("$name: and the same attribution rule on the tool path", $attributed ? ($s['referer'] ?? '') === 'https://freeitsm.co.uk' : ($s['referer'] ?? null) === null);
}
@unlink($LOG);

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
