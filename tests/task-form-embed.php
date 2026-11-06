<?php
/* NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Embedded task forms must grow AFTER their asynchronous load, and shrink when
 * conditional fields disappear. Observing a viewport-sized body left long forms
 * clipped at the loading card's 600px, with overflow hidden and no submit button.
 *
 * Run: php tests/task-form-embed.php
 * Requires Chrome, Chromium or Edge (CHROME_BIN, or an installed one). Nothing else:
 * the browser is driven from PHP over its DevTools socket (cdpEvaluate() below).
 * Uses the real fill-page styles, height reporter and task-window receiver in
 * temporary same-origin pages. No database, login, forms or settings are touched.
 * The pre-fix code fails the delayed-growth and shrink assertions (the control).
 */

/**
 * Evaluates a script in a page over Chrome's DevTools WebSocket and returns its
 * value - the smallest WebSocket client that does the job (RFC 6455: the client
 * masks what it sends; the server's frames arrive unmasked). Waits for the
 * script's promise.
 *
 * @param string $wsUrl  the page's webSocketDebuggerUrl, ws://127.0.0.1:port/devtools/page/ID
 * @param string $expression  JavaScript returning a value or a promise of one
 * @param int $timeout  seconds before giving up
 */
function cdpEvaluate(string $wsUrl, string $expression, int $timeout): string
{
    $u = parse_url($wsUrl);
    $sock = @stream_socket_client('tcp://' . $u['host'] . ':' . $u['port'], $errno, $error, 5);
    if (!$sock) throw new RuntimeException('Cannot reach the browser: ' . $error);
    stream_set_timeout($sock, $timeout);
    $key = base64_encode(random_bytes(16));
    fwrite($sock, "GET {$u['path']} HTTP/1.1\r\nHost: {$u['host']}:{$u['port']}\r\nUpgrade: websocket\r\nConnection: Upgrade\r\n"
                . "Sec-WebSocket-Key: $key\r\nSec-WebSocket-Version: 13\r\n\r\n");
    $head = '';
    while (!str_contains($head, "\r\n\r\n") && !feof($sock)) $head .= fread($sock, 1);
    if (!str_contains($head, ' 101 ')) throw new RuntimeException('The browser refused the DevTools connection.');

    $send = function (string $text) use ($sock) {
        $len = strlen($text);
        $frame = chr(0x81) . ($len < 126 ? chr(0x80 | $len) : ($len < 65536 ? chr(0x80 | 126) . pack('n', $len) : chr(0x80 | 127) . pack('J', $len)));
        $mask = random_bytes(4);
        $masked = '';
        for ($i = 0; $i < $len; $i++) $masked .= $text[$i] ^ $mask[$i % 4];
        fwrite($sock, $frame . $mask . $masked);
    };
    $read = function (int $n) use ($sock) {
        $buf = '';
        while (strlen($buf) < $n) {
            $chunk = fread($sock, $n - strlen($buf));
            if ($chunk === '' || $chunk === false) throw new RuntimeException('The browser closed the DevTools connection or timed out.');
            $buf .= $chunk;
        }
        return $buf;
    };
    $receive = function () use ($read) {
        $message = '';
        do {
            [$b1, $b2] = array_values(unpack('C2', $read(2)));
            $len = $b2 & 0x7f;
            if ($len === 126) $len = unpack('n', $read(2))[1];
            elseif ($len === 127) $len = unpack('J', $read(8))[1];
            $message .= $len ? $read($len) : '';
        } while (!($b1 & 0x80));
        return $message;
    };

    $send(json_encode(['id' => 1, 'method' => 'Runtime.evaluate',
        'params' => ['expression' => $expression, 'awaitPromise' => true, 'returnByValue' => true]]));
    $deadline = time() + $timeout;
    while (time() < $deadline) {
        $msg = json_decode($receive(), true);
        if (($msg['id'] ?? null) !== 1) continue;           // an event, not our answer
        fclose($sock);
        $value = $msg['result']['result']['value'] ?? null;
        if (!is_string($value)) throw new RuntimeException('Browser harness failed: ' . json_encode($msg));
        return $value;
    }
    fclose($sock);
    throw new RuntimeException('Browser test timed out.');
}
$root = dirname(__DIR__);
$browser = getenv('CHROME_BIN') ?: null;
foreach ([
    'C:/Program Files/Google/Chrome/Application/chrome.exe',
    'C:/Program Files (x86)/Google/Chrome/Application/chrome.exe',
    'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe',
    '/usr/bin/google-chrome', '/usr/bin/chromium', '/usr/bin/chromium-browser',
] as $candidate) {
    if (!$browser && is_file($candidate)) $browser = $candidate;
}
if (!$browser || !is_file($browser)) {
    fwrite(STDERR, "Set CHROME_BIN to a Chrome, Chromium or Edge executable.\n");
    exit(1);
}

$fill = file_get_contents($root . '/forms/fill.php');
$tasks = file_get_contents($root . '/assets/js/tasks.js');
// Lift the loaded CSS IN ORDER, including mobile.css after the embed overrides.
// A comment mentions "inline <style>"; it is not an actual style element.
$markup = preg_replace('~<!--.*?-->~s', '', $fill);
preg_match_all('~<link\b[^>]*href="\.\./(assets/css/[^"?]+)[^"]*"[^>]*>|<style>(.*?)</style>~s', $markup, $matches, PREG_SET_ORDER);
$styles = '';
foreach ($matches as $match) {
    $styles .= '<style>' . (!empty($match[1]) ? file_get_contents($root . '/' . $match[1]) : $match[2]) . '</style>';
}
$reportStart = strpos($fill, '// Embedded: say how tall');
$reportEnd = $reportStart === false ? false : strpos($fill, '/** Posts a task form action.', $reportStart);
$receiveStart = strpos($tasks, '// The embedded form says how tall');
$receiveEnd = $receiveStart === false ? false : strpos($tasks, "\n});", $receiveStart);
if (!$styles || $reportStart === false || $reportEnd === false || $receiveStart === false || $receiveEnd === false) {
    fwrite(STDERR, "Cannot locate the production embed styles or message handlers.\n");
    exit(1);
}
$reporter = substr($fill, $reportStart, $reportEnd - $reportStart);
$receiver = substr($tasks, $receiveStart, $receiveEnd + 5 - $receiveStart);
$frame = '<!doctype html><html><head><meta name="viewport" content="width=device-width,initial-scale=1">'
    . $styles . '</head><body class="fill-embed"><div class="main-container fill-container">'
    . '<div class="fill-content"><div class="fill-card" id="formCard">Loading</div></div></div><script>'
    . 'const TASK_FORM_ID = 1; const mode = new URLSearchParams(location.search).get("mode");'
    . 'if (mode === "poll") window.ResizeObserver = undefined;'
    . 'if (mode !== "no-reporter") {' . $reporter . '}</script></body></html>';
$parent = <<<'HTML'
<!doctype html><html><head><meta charset="utf-8"><style>
* { box-sizing: border-box; } body { margin: 0; }
</style><style>__TASK_CSS__</style></head><body>
<div id="taskFormsSection"><iframe id="tfFrame1" class="tf-frame" title="ZZ embed test"></iframe></div>
<pre id="result">RUNNING</pre><script>
__RECEIVER__
const results = [];
const frame = document.getElementById('tfFrame1');
const record = (label, pass, detail) => results.push({label, pass, detail});
const pause = ms => new Promise(resolve => setTimeout(() => requestAnimationFrame(() => requestAnimationFrame(resolve)), ms));
const load = mode => new Promise(resolve => { frame.onload = resolve; frame.src = 'frame.html?mode=' + mode; });
const fits = () => {
    const card = frame.contentDocument.getElementById('formCard');
    return card.getBoundingClientRect().bottom <= frame.clientHeight + 1;
};
const dimensions = () => ({frame:frame.clientHeight, card:frame.contentDocument.getElementById('formCard').offsetHeight});
async function exercise(mode, delay) {
    frame.style.width = '1000px';
    frame.style.height = '420px';
    await load(mode);
    await pause(delay);
    const doc = frame.contentDocument;
    const card = doc.getElementById('formCard');
    card.innerHTML = '<form><div id="conditional" style="height:1600px">ZZ delayed fields</div><button type="submit">Submit</button></form>';
    await pause(delay);
    record(mode + ': delayed fields and submit button fit', fits(), dimensions());
    const expanded = frame.clientHeight;
    doc.getElementById('conditional').style.display = 'none';
    await pause(delay);
    record(mode + ': hidden fields shrink the frame', fits() && frame.clientHeight < expanded - 500, dimensions());
    card.innerHTML = '<p>' + 'ZZ conditional explanation and instructions. '.repeat(100) + '</p><button>Submit</button>';
    await pause(delay);
    const wide = frame.clientHeight;
    frame.style.width = '360px';
    await pause(delay);
    record(mode + ': narrow layout grows without clipping', fits() && frame.clientHeight > wide + 100, dimensions());
    frame.style.width = '1000px';
    await pause(delay);
    record(mode + ': wider layout shrinks again', fits() && frame.clientHeight < wide + 5, dimensions());
}
(async () => {
    await exercise('observer', 180);
    await exercise('poll', 1100);
    frame.style.height = '420px';
    await load('no-reporter');
    frame.contentDocument.getElementById('formCard').innerHTML = '<div style="height:1800px">ZZ fallback content</div><button>Submit</button>';
    await pause(180);
    frame.contentWindow.scrollTo(0, 900);
    const overflow = frame.contentWindow.getComputedStyle(frame.contentDocument.body).overflowY;
    record('reporter unavailable: remaining content can still be scrolled', frame.contentWindow.scrollY > 100 && !['hidden', 'clip'].includes(overflow),
        {scrollY:frame.contentWindow.scrollY,overflow});
    document.getElementById('result').textContent = JSON.stringify(results);
})().catch(error => { document.getElementById('result').textContent = JSON.stringify([{label:'harness',pass:false,detail:String(error)}]); });
</script></body></html>
HTML;
$parent = str_replace(['__TASK_CSS__', '__RECEIVER__'], [file_get_contents($root . '/assets/css/tasks.css'), $receiver], $parent);

$tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'freeitsm_embed_' . bin2hex(random_bytes(6));
mkdir($tmp, 0700);
$server = null;
$browserProcess = null;
$exit = 1;
try {
    file_put_contents($tmp . '/index.html', $parent);
    file_put_contents($tmp . '/frame.html', $frame);
    // Let the OS pick a free loopback port; no existing web server is involved.
    $socket = stream_socket_server('tcp://127.0.0.1:0', $errno, $error);
    if (!$socket) throw new RuntimeException('Cannot allocate a loopback port.');
    $address = stream_socket_get_name($socket, false);
    fclose($socket);
    $port = (int)substr(strrchr($address, ':'), 1);
    $server = proc_open([PHP_BINARY, '-S', '127.0.0.1:' . $port, '-t', $tmp],
        [0 => ['pipe', 'r'], 1 => ['file', $tmp . '/server.log', 'a'], 2 => ['file', $tmp . '/server.log', 'a']], $serverPipes);
    if (!is_resource($server)) throw new RuntimeException('Cannot start the fixture server.');
    fclose($serverPipes[0]);
    $ready = false;
    for ($i = 0; $i < 40; $i++) {
        $connection = @fsockopen('127.0.0.1', $port, $errno, $error, 0.1);
        if ($connection) { fclose($connection); $ready = true; break; }
        usleep(50000);
    }
    if (!$ready) throw new RuntimeException('Fixture server did not become ready.');
    $debugSocket = stream_socket_server('tcp://127.0.0.1:0', $errno, $error);
    if (!$debugSocket) throw new RuntimeException('Cannot allocate the browser debugging port.');
    $debugPort = (int)substr(strrchr(stream_socket_get_name($debugSocket, false), ':'), 1);
    fclose($debugSocket);
    $browserProcess = proc_open([$browser, '--headless', '--disable-gpu', '--no-first-run', '--no-default-browser-check',
        '--disable-background-networking', '--remote-debugging-port=' . $debugPort,
        '--user-data-dir=' . $tmp . '/profile', 'http://127.0.0.1:' . $port . '/index.html'],
        [0 => ['pipe', 'r'], 1 => ['file', $tmp . '/browser.log', 'a'], 2 => ['file', $tmp . '/browser.log', 'a']], $browserPipes);
    if (!is_resource($browserProcess)) throw new RuntimeException('Cannot start headless Chromium.');
    fclose($browserPipes[0]);
    // Real animation frames matter to ResizeObserver, and the page waits on
    // them: a virtual-time --dump-dom run never advances them and the checks
    // stall. So the browser runs in real time and is asked for the result over
    // its DevTools socket.
    $page = null;
    for ($i = 0; $i < 50 && !$page; $i++) {
        $list = @file_get_contents('http://127.0.0.1:' . $debugPort . '/json/list');
        foreach ((array)json_decode((string)$list, true) as $p) {
            if (($p['type'] ?? '') === 'page' && substr($p['url'] ?? '', -11) === '/index.html') { $page = $p; break; }
        }
        if (!$page) usleep(100000);
    }
    if (!$page) throw new RuntimeException('Headless browser page unavailable.');
    $value = cdpEvaluate($page['webSocketDebuggerUrl'],
        "new Promise(resolve => { const poll = setInterval(() => { const r = document.getElementById('result'); if (r && r.textContent.startsWith('[')) { clearInterval(poll); resolve(r.textContent); } }, 100); })",
        25);
    file_put_contents($tmp . '/result.json', $value);
    $checks = json_decode(file_get_contents($tmp . '/result.json'), true);
    if (!is_array($checks) || count($checks) !== 9) throw new RuntimeException('Browser did not finish all nine checks.');
    $failed = 0;
    foreach ($checks as $check) {
        echo ($check['pass'] ? '  PASS  ' : '  FAIL  ') . $check['label'] . ' ' . json_encode($check['detail']) . "\n";
        if (!$check['pass']) $failed++;
    }
    echo (count($checks) - $failed) . " passed, $failed failed\n";
    $exit = $failed ? 1 : 0;
} catch (Throwable $e) {
    $message = str_replace([$tmp, getenv('USERPROFILE') ?: $tmp], ['[temporary directory]', '[user profile]'], $e->getMessage());
    fwrite(STDERR, $message . "\n");
} finally {
    foreach ([$browserProcess, $server] as $process) {
        if (is_resource($process)) { proc_terminate($process); proc_close($process); }
    }
    // Only our unique temporary directory, never a user's existing profile.
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tmp, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        if ($file->isDir()) @rmdir($file->getPathname());
        else @unlink($file->getPathname());
    }
    @rmdir($tmp);
}
exit($exit);
