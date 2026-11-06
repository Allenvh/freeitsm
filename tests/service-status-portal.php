<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Internal and external incident updates (discussion #99).
 *
 * The point of this file is that an INTERNAL update never reaches the portal.
 * Everything else is secondary, so each check is written from the side that
 * must be refused as well as the side that must work.
 *
 * ⚠️ Touches the database. Everything it makes is named ZZSS and removed in the
 * cleanup at the bottom, including on failure. It also puts the portal settings
 * back exactly as it found them — they are global, and a test that leaves the
 * portal publishing would be worse than no test.
 *
 * Run:  php tests/service-status-portal.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/service_status.php';
require_once __DIR__ . '/../includes/service_status_portal.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

echo "\nService status: internal vs external updates (#99)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ctx = ActorContext::fromSession($conn);

// ⚠️ ONE connection throughout. An earlier version opened fresh ones to dodge a
// `static` cache in the settings reader — which does not work, because a static
// is per PROCESS. The cache is gone; the settings are read when they are asked
// for, which is also what a settings page saving and re-rendering needs.
$settingKeys = array_keys(SS_PORTAL_DEFAULTS);
$before = [];
$q = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
foreach ($settingKeys as $k) { $q->execute([$k]); $before[$k] = $q->fetchColumn(); }

function setSetting(PDO $conn, string $k, string $v): void {
    $conn->prepare("INSERT INTO system_settings (setting_key, setting_value, updated_datetime)
                         VALUES (?, ?, UTC_TIMESTAMP())
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)")->execute([$k, $v]);
}
/** Portal incidents keyed by id, for readable assertions. */
function portalById(PDO $conn): array {
    $out = [];
    foreach (ssPortalIncidents($conn) as $i) { $out[$i['id']] = $i; }
    return $out;
}

$made = [];

try {
    // ── 1. Off by default ───────────────────────────────────────────────────
    echo "\nThe default:\n";
    foreach ($settingKeys as $k) {
        $conn->prepare("DELETE FROM system_settings WHERE setting_key = ?")->execute([$k]);
    }
    ok('with nothing configured, the portal shows nothing',
       ssPortalUpdatesEnabled($conn) === false,
       'an upgrade would have started publishing incident titles');

    setSetting($conn, 'service_status_portal_updates', '1');
    setSetting($conn, 'service_status_portal_mode', 'all');

    // ── 2. Internal never reaches the portal ────────────────────────────────
    echo "\nInternal never reaches the portal:\n";
    $id = ServiceStatusService::saveIncident($conn, $ctx, [
        'title'       => 'ZZSS Mail delays',
        'status'      => 'Investigating',
        'comment'     => 'ZZSS INTERNAL: DAG failover, node 3 is the culprit',
        'is_internal' => true,
    ]);
    $made[] = $id;

    ok('an incident with only internal updates has none to show',
       ssPortalIncidentUpdates($conn, $id) === []);
    ok('and does not appear on the portal at all', !isset(portalById($conn)[$id]),
       'an incident with nothing but internal notes was listed');

    ServiceStatusService::saveIncident($conn, $ctx, [
        'id'          => $id,
        'comment'     => 'ZZSS EXTERNAL: we are investigating delays to email',
        'is_internal' => false,
    ]);

    $u = ssPortalIncidentUpdates($conn, $id);
    ok('once an external update exists, exactly one is shown', count($u) === 1, count($u) . ' shown');
    ok('and it is the external one',
       $u && str_contains($u[0]['comment'], 'EXTERNAL'),
       $u ? $u[0]['comment'] : '(nothing)');
    ok('the internal one is NOT in the list',
       !str_contains(json_encode($u), 'INTERNAL'),
       'internal text reached a portal-facing function');
    ok('now the incident appears on the portal', isset(portalById($conn)[$id]));
    ok('and it says how many external updates there are',
       (portalById($conn)[$id]['update_count'] ?? 0) === 1);

    // ── 3. What is deliberately not sent ────────────────────────────────────
    ok('no author reaches the portal', $u && !array_key_exists('created_by_id', $u[0]));

    // ── 4. Defaulting ───────────────────────────────────────────────────────
    echo "\nDefaulting, for callers that have never heard of this:\n";
    $id2 = ServiceStatusService::saveIncident($conn, $ctx, [
        'title'   => 'ZZSS No flag given',
        'status'  => 'Investigating',
        'comment' => 'ZZSS a caller that does not know about is_internal',
    ]);
    $made[] = $id2;
    ok('an update with no flag is INTERNAL', ssPortalIncidentUpdates($conn, $id2) === [],
       'an update defaulted to public');

    // ── 5. The master switch ────────────────────────────────────────────────
    echo "\nThe master switch:\n";
    setSetting($conn, 'service_status_portal_updates', '0');
    ok('switched off, an incident with external updates is hidden', ssPortalIncidents($conn) === []);
    ok('and its updates are hidden too', ssPortalIncidentUpdates($conn, $id) === []);
    setSetting($conn, 'service_status_portal_updates', '1');
    ok('POSITIVE CONTROL: switching it back on shows it again', isset(portalById($conn)[$id]));

    // ── 6. What counts as showable ──────────────────────────────────────────
    echo "\nOpen, recent and all:\n";
    setSetting($conn, 'service_status_portal_mode', 'open');
    ok('an open incident shows in "open" mode', isset(portalById($conn)[$id]));

    ServiceStatusService::saveIncident($conn, $ctx, [
        'id' => $id, 'status' => 'Resolved',
        'comment' => 'ZZSS EXTERNAL: fixed', 'is_internal' => false,
    ]);
    ok('and is gone once resolved', !isset(portalById($conn)[$id]));

    setSetting($conn, 'service_status_portal_mode', 'recent');
    setSetting($conn, 'service_status_portal_days', '7');
    ok('but "recent" still shows it', isset(portalById($conn)[$id]),
       'somebody hit by the outage this morning could not see it was fixed');

    // Resolved just now, so a window of 0 days is the only way to exclude it —
    // which the setting clamps to 1. Prove the clamp instead.
    setSetting($conn, 'service_status_portal_days', '0');
    ok('a nonsense window is clamped rather than obeyed', isset(portalById($conn)[$id]));

    setSetting($conn, 'service_status_portal_mode', 'all');
    ok('"all" shows it too', isset(portalById($conn)[$id]));

} finally {
    try {
        foreach ($made as $id) {
            $conn->prepare("DELETE FROM status_incidents WHERE id = ?")->execute([$id]);
        }
        $conn->prepare("DELETE FROM status_incidents WHERE title LIKE 'ZZSS%'")->execute();
        foreach ($settingKeys as $k) {
            if ($before[$k] === false || $before[$k] === null) {
                $conn->prepare("DELETE FROM system_settings WHERE setting_key = ?")->execute([$k]);
            } else {
                setSetting($conn, $k, (string)$before[$k]);
            }
        }
        $left = (int)$conn->query("SELECT COUNT(*) FROM status_incidents WHERE title LIKE 'ZZSS%'")->fetchColumn();
        $stillOn = ssPortalUpdatesEnabled($conn) ? ' ⚠️  PORTAL LEFT ENABLED' : '';
        echo "\n  cleanup: " . ($left === 0 ? "no ZZSS rows left, settings restored" : "⚠️  {$left} ZZSS rows REMAIN") . $stillOn . "\n";
    } catch (Exception $e) {
        echo "\n  ⚠️  cleanup failed: " . $e->getMessage() . "\n";
    }
}

echo str_repeat('=', 70) . "\n  {$pass} passed, {$fail} failed\n\n";
exit($fail === 0 ? 0 : 1);
