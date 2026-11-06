<?php
/* 🔴 NEVER OVER THE WEB. See tests/web-exposure-guard.php. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Every Cap:: in the codebase is used the safe way (4.0).
 *
 *   php tests/cap-enum-usage.php
 *
 * Cap became an enum in 4.0 (PHP 8.1 floor). An enum is not a string: echoed or
 * used as an array key it errors loudly, but COMPARED with a string - ===,
 * in_array(), a switch - it is silently false, so a permission check quietly
 * denies or a safeguard is quietly skipped. The rule (includes/capabilities.php)
 * is mechanical, so this checks it mechanically, with PHP's own tokenizer:
 *
 *   1. A bare Cap::X only as the direct argument of a guard. Anywhere else it
 *      must be capKey(Cap::X) - never Cap::X->value, which is PHP 8 syntax: a
 *      settings page holding it would not even parse on an old PHP, so the
 *      version guard could never say "this needs PHP 8.1".
 *   2. A guard's capability argument is Cap::X, Cap::from(...), or - inside the
 *      guard wrappers themselves - their own Cap-typed parameter. Never a raw
 *      string or array element, which would be a TypeError at run time.
 *   3. Every guard function declares its capability parameter as Cap.
 *   4. The registry agrees with the enum (capSelfCheck()), and the enum's
 *      methods answer the way the string helpers do.
 *
 * Reads files only, plus the capability registry (the settings manifests).
 */

$root = str_replace('\\', '/', dirname(__DIR__));
require_once $root . '/includes/capabilities.php';
if (is_file($root . '/includes/settings_keys.php')) require_once $root . '/includes/settings_keys.php';

$pass = 0; $fail = 0;
function check(string $label, bool $ok, string $detail = ''): void {
    global $pass, $fail;
    if ($ok) { $pass++; echo "  ok    $label\n"; }
    else     { $fail++; echo "  FAIL  $label" . ($detail !== '' ? "\n          " . str_replace("\n", "\n          ", $detail) : '') . "\n"; }
}

/** Functions whose capability argument must be a Cap. => [file that defines it, parameter name] */
const GUARDS = [
    'requireCapabilityJson' => ['includes/rbac.php', 'capability'],
    'requireCapability'     => ['includes/rbac.php', 'capability'],
    'requireActionJson'     => ['includes/rbac.php', 'capability'],
    'analystHasCapability'  => ['includes/rbac.php', 'capability'],
    'analystMayAct'         => ['includes/rbac.php', 'capability'],
    'capActionRestricted'   => ['includes/rbac.php', 'capability'],
    'domainRequireCap'      => ['includes/domains/api_bootstrap.php', 'cap'],
    'domainHasCap'          => ['includes/domains/api_bootstrap.php', 'cap'],
    'filesHasCap'           => ['includes/files/api_bootstrap.php', 'cap'],
];
/** Where a guard may be handed its own Cap-typed parameter rather than Cap::... */
const PASS_THROUGH_FILES = ['includes/rbac.php', 'includes/domains/api_bootstrap.php', 'includes/files/api_bootstrap.php', 'files/index.php'];

/**
 * Scan one file's tokens.
 *
 * @return array{bare:array, guardArgs:array}  bare = Cap::X not in a guard and without ->value;
 *               guardArgs = [line, function, first tokens of the argument] for each guard call
 */
function scanCapUsage(string $src): array
{
    $t = token_get_all($src);
    $n = count($t);
    $sig = function (int $from, int $dir) use ($t, $n) {     // next significant token index
        for ($k = $from; $k >= 0 && $k < $n; $k += $dir) {
            if (is_array($t[$k]) && in_array($t[$k][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) continue;
            return $k;
        }
        return -1;
    };
    $text = fn(int $k) => $k < 0 ? '' : (is_array($t[$k]) ? $t[$k][1] : $t[$k]);
    $bare = []; $guardArgs = [];
    $guardNames = array_merge(array_keys(GUARDS), ['$hasCap']);

    for ($i = 0; $i < $n; $i++) {
        // ---- rule 1: Cap::X
        if (is_array($t[$i]) && $t[$i][1] === 'Cap' && $text($i + 1) === '::'
            && isset($t[$i + 2]) && is_array($t[$i + 2]) && preg_match('/^[A-Z0-9_]+$/', $t[$i + 2][1])) {
            $after = $sig($i + 3, 1);
            if ($text($after) === '->') {                         // PHP 8 syntax in a page
                $bare[] = $t[$i][2] . ': Cap::' . $t[$i + 2][1] . '-> (write capKey(Cap::' . $t[$i + 2][1] . '))';
                continue;
            }
            // enclosing call at depth 0
            $depth = 0; $call = null;
            for ($k = $i - 1; $k >= 0; $k--) {
                $tk = $text($k);
                if ($tk === ')' || $tk === ']') $depth++;
                elseif ($tk === '(' || $tk === '[') {
                    if ($depth === 0) { if ($tk === '(') $call = $text($sig($k - 1, -1)); break; }
                    $depth--;
                } elseif (in_array($tk, [';', '{', '}'], true) && $depth === 0) break;
            }
            if ($call === null || ($call !== 'capKey' && !in_array($call, $guardNames, true))) $bare[] = $t[$i][2] . ': Cap::' . $t[$i + 2][1];
            continue;
        }
        // ---- rule 2: guard calls
        if (is_array($t[$i]) && in_array($t[$i][1], $guardNames, true) && $text($sig($i + 1, 1)) === '(') {
            $prev = $sig($i - 1, -1);
            if ($text($prev) === 'function' || $text($sig($i + 1, 1)) !== '(') continue;   // a definition
            if (in_array($text($prev), ['->', '::'], true)) continue;                       // a method of something else
            $name = $t[$i][1];
            // which argument holds the capability: requireX(cap, ...) first; has/may (conn, id, cap) third;
            // capActionRestricted / domainRequireCap (conn, cap) second; $hasCap(cap) first.
            $pos = ['requireCapabilityJson' => 0, 'requireCapability' => 0, 'requireActionJson' => 0, '$hasCap' => 0,
                    'analystHasCapability' => 2, 'analystMayAct' => 2, 'domainHasCap' => 2, 'filesHasCap' => 2,
                    'capActionRestricted' => 1, 'domainRequireCap' => 1][$name];
            $open = $sig($i + 1, 1); $depth = 0; $arg = 0; $argText = '';
            for ($k = $open + 1; $k < $n; $k++) {
                $tk = $text($k);
                if (in_array($tk, ['(', '[', '{'], true)) $depth++;
                elseif (in_array($tk, [')', ']', '}'], true)) { if ($depth === 0) break; $depth--; }
                elseif ($tk === ',' && $depth === 0) { $arg++; continue; }
                if ($arg === $pos) $argText .= $tk;
            }
            $guardArgs[] = [$t[$i][2], $name, trim(preg_replace('/\s+/', ' ', $argText))];
        }
    }
    return ['bare' => $bare, 'guardArgs' => $guardArgs];
}

echo "Every Cap:: is used the safe way (Cap is an enum, 4.0)\n";
echo str_repeat('=', 72) . "\n";

// ---------------------------------------------------------------- the scanner
echo "\nThe scanner\n";
$s = scanCapUsage("<?php\nrequireCapabilityJson(Cap::A);\n\$x = ['cap' => Cap::B];\n\$y = capKey(Cap::C);\nif (\$k === Cap::D) {}\nanalystHasCapability(\$conn, \$id, Cap::E);\nrequireCapabilityJson(\$tab['cap']);\nrequireCapabilityJson(Cap::from(\$s));\n\$z = Cap::F->value;\n");
check('CONTROL: an array value without capKey() is flagged', in_array('3: Cap::B', $s['bare'], true), json_encode($s['bare']));
check('CONTROL: a comparison without capKey() is flagged', in_array('5: Cap::D', $s['bare'], true), json_encode($s['bare']));
check('CONTROL: Cap::X->value is flagged (PHP 8 syntax in a page)', (bool)preg_grep('/^9: Cap::F->/', $s['bare']), json_encode($s['bare']));
check('guard arguments and capKey() are not flagged', count($s['bare']) === 3, json_encode($s['bare']));
$args = array_column($s['guardArgs'], 2);
check('guard arguments are read', $args === ['Cap::A', 'Cap::E', "\$tab['cap']", 'Cap::from($s)'], json_encode($args));

// ---------------------------------------------------------------- the codebase
echo "\nThe codebase\n";
$bad = []; $badArgs = []; $files = 0; $guardCalls = 0; $caps = 0;
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    if ($f->getExtension() !== 'php') continue;
    $p = str_replace('\\', '/', $f->getPathname());
    if (preg_match('#/(\.git|vendor|local|node_modules)/#', $p)) continue;
    $rel = substr($p, strlen($root) + 1);
    if ($rel === 'tests/cap-enum-usage.php') continue;     // its own fixtures
    $src = file_get_contents($p);
    if (!str_contains($src, 'Cap::') && !preg_match('/\b(requireCapability|requireActionJson|analystHasCapability|analystMayAct|capActionRestricted|domainRequireCap|domainHasCap|filesHasCap)\b/', $src)) continue;
    $files++;
    $r = scanCapUsage($src);
    $caps += substr_count($src, 'Cap::');
    foreach ($r['bare'] as $b) $bad[] = "$rel:$b";
    foreach ($r['guardArgs'] as [$line, $fn, $arg]) {
        $guardCalls++;
        $ok = preg_match('/^Cap::([A-Z0-9_]+|from\()/', $arg)
           || (in_array($rel, PASS_THROUGH_FILES, true) && in_array($arg, ['$capability', '$cap'], true));
        if (!$ok) $badArgs[] = "$rel:$line $fn( $arg )";
    }
}
check("CONTROL: scanned real code ($files files, $guardCalls guard calls - more than 250)", $guardCalls > 250);
check('rule 1: no bare Cap::X outside a guard or capKey(), and no Cap::X->value', !$bad, implode("\n", $bad));
check('rule 2: every guard is handed Cap::X, Cap::from(), or its own Cap parameter', !$badArgs, implode("\n", $badArgs));

// ---------------------------------------------------------------- rule 3
echo "\nGuard signatures\n";
foreach (GUARDS as $fn => [$file, $param]) {
    $src = file_get_contents("$root/$file");
    check("$fn() takes Cap \$$param", (bool)preg_match('/function ' . $fn . '\s*\([^)]*\bCap \$' . $param . '\b/', $src));
}
$fi = file_get_contents("$root/files/index.php");
check('files/index.php $hasCap takes Cap $cap', str_contains($fi, '$hasCap = function (Cap $cap)'));

// ---------------------------------------------------------------- rule 4
echo "\nThe enum and the registry\n";
check('Cap is an enum', (new ReflectionClass('Cap'))->isEnum());
check('136 or more cases', count(Cap::cases()) >= 136, (string)count(Cap::cases()));
$problems = capSelfCheck();
check('capSelfCheck() finds nothing', !$problems, implode("\n", $problems));
$mismatch = [];
foreach (Cap::cases() as $c) {
    if ($c->label() !== capLabel($c->value) || $c->module() !== capModule($c->value)
        || $c->isSensitive() !== capIsSensitive($c->value) || $c->isUmbrella() !== capIsUmbrella($c->value)) $mismatch[] = $c->name;
}
check('every case\'s methods agree with the string helpers', !$mismatch, implode(', ', $mismatch));
check('Cap::fromKey() resolves a real key', Cap::fromKey('tickets.sla') === Cap::TICKETS_SLA);
check('Cap::fromKey() refuses an unknown key', Cap::fromKey('tickets.nope') === null);
check('Cap::tryFromName() finds a case by name', Cap::tryFromName('TICKETS_SLA') === Cap::TICKETS_SLA);
$forTickets = Cap::forModule('tickets');
check('Cap::forModule() returns cases', $forTickets && $forTickets[0] instanceof Cap && in_array(Cap::TICKETS_SLA, $forTickets, true));
$threw = false; try { Cap::from('tickets.nope'); } catch (ValueError $e) { $threw = true; }
check('CONTROL: Cap::from() on an unknown key throws (loud, not a silent deny)', $threw);
$threw = false;
try { (function (Cap $c) {})('tickets.sla'); } catch (TypeError $e) { $threw = true; }
check('CONTROL: a Cap parameter refuses a string', $threw);

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
