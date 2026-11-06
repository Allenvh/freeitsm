<?php
/**
 * Scaffold catalogue entries for routes that aren't documented yet.
 *
 *   php api/v1/dev/openapi_stub.php
 *
 * Compares the route table (lib/routes.php) with the catalogue (spec.json) and
 * prints a ready-to-paste `spec.json` item for every route that has no entry —
 * method, path (with the right `{id}` placeholders), the permission tuple, and
 * stub `s` / `d` / `params` you fill in. Paste each into the matching section's
 * `items` array in api/v1/spec.json, flesh it out, then run openapi_fix.php +
 * openapi_check.php. Prints nothing when everything is already documented.
 */
if (PHP_SAPI !== 'cli') { http_response_code(403); exit("CLI only.\n"); }

$routes = require __DIR__ . '/../lib/routes.php';
$spec   = json_decode(file_get_contents(__DIR__ . '/../spec.json'), true);

$documented = [];
foreach ($spec['spec'] as $sec) foreach ($sec['items'] as $it) {
    $probe = preg_replace('/\{[^}]+\}/', '1', $it['p']);
    foreach ($routes as $i => [$m, $pat]) if ($m === $it['m'] && preg_match($pat, $probe)) { $documented[$i] = true; break; }
}

$stubs = [];
foreach ($routes as $i => [$method, $pattern, $perm, $handler]) {
    if (!empty($documented[$i])) continue;
    // Recover a readable path template from the regex: strip anchors/escapes,
    // turn each capture group into a {param}. Names are guessed from position.
    $path = $pattern;
    $path = preg_replace('/^#\^?|\$?#$/', '', $path);   // drop # anchors
    $path = str_replace(['\\/', '\\.', '\\-'], ['/', '.', '-'], $path);
    $n = 0;
    $path = preg_replace_callback('/\([^)]*\)/', function () use (&$n) {
        $n++;
        return '{' . ($n === 1 ? 'id' : 'id' . $n) . '}';
    }, $path);
    $permStr = is_array($perm) ? "{$perm[0]}.{$perm[1]}" : 'none';
    $hasBody = in_array($method, ['POST', 'PATCH'], true);
    $stub  = "        {m: '{$method}', p: '{$path}', perm: '{$permStr}', s: 'TODO one-line summary',\n";
    $stub .= "         d: 'TODO description.',\n";
    $stub .= "         params: [" . (str_contains($path, '{') ? "P('id', 'path', 'TODO', true)" : "") . "]"
           . ($hasBody ? ",\n         body: { /* TODO example */ }" : "") . "},";
    $stubs[] = "  // handler: {$handler}\n" . $stub;
}

if (!$stubs) { echo "All " . count($routes) . " routes are documented in spec.json — nothing to scaffold.\n"; exit(0); }
echo "// " . count($stubs) . " undocumented route(s). Paste each into the right section's items[] in api/v1/spec.json:\n\n";
echo implode("\n\n", $stubs) . "\n";
