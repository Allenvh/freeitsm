<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * RFPs belong to a company (3.5.0) - includes/rfp_scope.php.
 *
 *   php tests/rfp-companies.php
 *
 * Two halves:
 *  1. STRUCTURE - every RFP Builder endpoint calls rfpGate() (or, for the five
 *     that stream events, rfpCanView()), apart from the few that are about no
 *     RFP at all. A new endpoint that forgets the gate fails here, before it
 *     can read another company's tender documents.
 *  2. BEHAVIOUR - the helpers, in a transaction that is ALWAYS rolled back:
 *     every kind of child id finds its RFP, an analyst limited to one company
 *     sees that company's RFPs and no other, the list follows the switcher -
 *     each "cannot" paired with a positive control, so a filter that hides
 *     everything cannot pass.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';
require_once __DIR__ . '/../includes/rfp_scope.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

echo "\nRFPs belong to a company (3.5.0)\n" . str_repeat('=', 70) . "\n";

// ── 1. Every endpoint is gated ──────────────────────────────────────────────
echo "\nEvery RFP endpoint passes the gate:\n";
// About no RFP at all: AI settings, the shared departments list, and the list
// itself (which is scoped by rfpListSql instead).
$noRfp = ['get_ai_settings.php', 'save_ai_settings.php', 'test_ai_connection.php',
          'get_rfp_departments.php', 'save_rfp_department.php', 'delete_rfp_department.php'];
$files = glob(__DIR__ . '/../api/rfp-builder/*.php');
ok('POSITIVE CONTROL: the endpoints were found', count($files) > 40, (string)count($files));
$ungated = [];
foreach ($files as $f) {
    $b = basename($f);
    if (in_array($b, $noRfp, true)) continue;
    $s = file_get_contents($f);
    if ($b === 'get_rfps.php') { if (!str_contains($s, 'rfpListSql(')) $ungated[] = $b; continue; }
    if (!str_contains($s, 'rfpGate(') && !str_contains($s, 'rfpCanView(')) $ungated[] = $b;
}
ok('no endpoint is missing the gate', !$ungated, implode(', ', $ungated));
foreach ($noRfp as $b) {
    ok("$b still exists (else take it off the exempt list)", is_file(__DIR__ . '/../api/rfp-builder/' . $b));
}
$preview = file_get_contents(__DIR__ . '/../contracts/rfp-builder/preview.php');
ok('the print preview, which reads the database itself, checks too', str_contains($preview, 'rfpCanView('));

// ── 2. Behaviour ────────────────────────────────────────────────────────────
$conn = connectToDatabase();
if (!rfpTenantReady($conn)) { echo "  SKIP  run Database Verification first\n"; goto done; }
if (!isMultiTenant($conn)) { echo "  SKIP  behaviour needs two or more companies\n"; goto done; }

$default = getDefaultTenantId($conn);
$others  = array_values(array_diff(array_map('intval', $conn->query("SELECT id FROM tenants WHERE is_active = 1 ORDER BY id")->fetchAll(PDO::FETCH_COLUMN)), [$default]));
if (count($others) < 2) { echo "  SKIP  needs two companies besides Default\n"; goto done; }
[$mine, $theirs] = $others;
$r = 0;
foreach ($conn->query("SELECT id FROM analysts WHERE is_active = 1 AND (is_admin = 0 OR is_admin IS NULL) ORDER BY id")->fetchAll(PDO::FETCH_COLUMN) as $cand) {
    if (analystCanAccessModule($conn, (int)$cand, 'contracts')) { $r = (int)$cand; break; }
}
if (!$r) { echo "  SKIP  needs a non-admin analyst with Contracts\n"; goto done; }
$admin = 1;

$conn->beginTransaction();
try {
    $conn->prepare("UPDATE analysts SET can_access_all_tenants = 0 WHERE id = ?")->execute([$r]);
    $conn->prepare("DELETE FROM analyst_tenant_access WHERE analyst_id = ?")->execute([$r]);
    $conn->prepare("DELETE FROM analyst_teams WHERE analyst_id = ?")->execute([$r]);
    $conn->prepare("INSERT INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?)")->execute([$r, $mine]);
    ok("analyst {$r} is limited to company {$mine}", getAccessibleTenantIds($conn, $r) === [$mine]);

    $mkRfp = function (?int $tenant) use ($conn, $default): int {
        $conn->prepare("INSERT INTO rfps (name, tenant_id) VALUES (?, ?)")->execute(['ZZRFP test ' . bin2hex(random_bytes(3)), $tenant === $default ? null : $tenant]);
        return (int)$conn->lastInsertId();
    };
    $rMine = $mkRfp($mine); $rTheirs = $mkRfp($theirs); $rDefault = $mkRfp($default);

    echo "\nWho sees which RFP:\n";
    ok('restricted analyst sees their company\'s RFP (POSITIVE CONTROL)', rfpCanView($conn, $r, $rMine));
    ok('restricted analyst does NOT see another company\'s', !rfpCanView($conn, $r, $rTheirs));
    ok('...nor a Default one', !rfpCanView($conn, $r, $rDefault));
    ok('admin sees all three (POSITIVE CONTROL)', rfpCanView($conn, $admin, $rMine) && rfpCanView($conn, $admin, $rTheirs) && rfpCanView($conn, $admin, $rDefault));
    ok('an RFP that does not exist is not visible either', !rfpCanView($conn, $admin, 999999999));
    ok('Default is stored as NULL', $conn->query("SELECT tenant_id FROM rfps WHERE id = $rDefault")->fetchColumn() === null);
    ok('rfpTenantOf reads NULL as Default', rfpTenantOf($conn, $rDefault) === $default && rfpTenantOf($conn, $rTheirs) === $theirs);

    [$sql, $args] = rfpVisibilitySql($conn, $r, 'r');
    $st = $conn->prepare("SELECT r.id FROM rfps r WHERE r.id IN (?, ?, ?)$sql");
    $st->execute(array_merge([$rMine, $rTheirs, $rDefault], $args));
    ok('the filter returns exactly their own', array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN)) === [$rMine]);

    echo "\nEvery kind of child id finds its RFP:\n";
    $conn->prepare("INSERT INTO rfp_documents (rfp_id, original_filename) VALUES (?, 'zz.txt')")->execute([$rTheirs]);
    $doc = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO rfp_extracted_requirements (rfp_id, document_id, requirement_text) VALUES (?, ?, 'zz')")->execute([$rTheirs, $doc]);
    $ext = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO rfp_consolidated_requirements (rfp_id, requirement_text) VALUES (?, 'zz')")->execute([$rTheirs]);
    $con = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO rfp_output_sections (rfp_id, section_content) VALUES (?, 'zz')")->execute([$rTheirs]);
    $sec = (int)$conn->lastInsertId();
    foreach (['document' => $doc, 'extracted' => $ext, 'consolidated' => $con, 'section' => $sec] as $kind => $id) {
        ok("a {$kind} id resolves to its RFP", rfpIdOf($conn, $kind, $id) === $rTheirs, (string)rfpIdOf($conn, $kind, $id));
    }
    ok('an unknown kind resolves to nothing', rfpIdOf($conn, 'nonsense', $doc) === null);
    ok('...and so a restricted analyst is refused that document\'s RFP', !rfpCanView($conn, $r, (int)rfpIdOf($conn, 'document', $doc)));
} catch (Throwable $e) {
    ok('the run completed', false, get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine());
} finally {
    if ($conn->inTransaction()) $conn->rollBack();
}
ok('nothing survived the run (rolled back)', (int)$conn->query("SELECT COUNT(*) FROM rfps WHERE name LIKE 'ZZRFP test%'")->fetchColumn() === 0);

done:
echo "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
