<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Read only module access (#180 Part 3 §4, slice F).
 *
 *   php tests/read-only-access.php [base_url]
 *
 * Its own file on purpose: module access is cached for the life of a request (a PHP
 * process), so a test that grants Read only to an analyst already looked up earlier in
 * the same run would read the old answer and pass or fail for the wrong reason.
 *
 * Runs against the real database. It gives a restricted non-admin a temporary Read only
 * grant on Change Management - only if they have no grant there already - and a ZZ
 * change, and removes both in the finally block.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/changes.php';

$BASE = isset($argv[1]) ? rtrim($argv[1], '/') . '/' : null;
$pass = 0; $fail = 0;
/**
 * Records one check.
 *
 * @param string $what  what is being proved
 * @param bool $cond  true when it holds
 * @param string $detail  shown on failure
 */
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
$code = function (callable $fn): string {
    try { $fn(); return ''; } catch (ServiceError $e) { return $e->errorCode; }
};

$conn = connectToDatabase();
// A restricted non-admin with NO Change Management grant - found by SQL, so no access
// helper has cached them before the grant below.
$plain = (int)$conn->query("SELECT a.id FROM analysts a WHERE a.is_active = 1 AND a.is_admin = 0 AND a.can_access_all_modules = 0
    AND NOT EXISTS (SELECT 1 FROM analyst_modules m WHERE m.analyst_id = a.id AND m.module_key = 'changes')
    AND NOT EXISTS (SELECT 1 FROM analyst_teams at WHERE at.analyst_id = a.id) ORDER BY a.id LIMIT 1")->fetchColumn();
$admin = (int)$conn->query("SELECT id FROM analysts WHERE is_active = 1 AND is_admin = 1 ORDER BY id LIMIT 1")->fetchColumn();
if (!$plain || !$admin) { echo "Needs a restricted non-admin with no Change Management grant and no team, and an admin.\n"; exit(1); }
$ctxPlain = new ActorContext($plain, null, 'ui', 'en', 'ZZ plain');
$ctxAdmin = new ActorContext($admin, null, 'ui', 'en', 'ZZ admin');
$made = ['changes' => [], 'grant' => false];

echo "\nRead only module access (#180 Part 3)\n" . str_repeat('=', 70) . "\n";
try {
    $conn->prepare("INSERT INTO analyst_modules (analyst_id, module_key, access_level) VALUES (?, 'changes', 'read')")->execute([$plain]);
    $made['grant'] = true;

    ok('a Read only grant is access', analystCanAccessModule($conn, $plain, 'changes'));
    ok('at the read level', analystModuleLevel($conn, $plain, 'changes') === 'read');
    ok('so they cannot raise a change', $code(fn() => ChangesService::createChange($conn, $ctxPlain, 1, ['title' => 'ZZ-RO refused'])) === 'read_only');
    $id = ChangesService::createChange($conn, $ctxAdmin, 1, ['title' => 'ZZ-RO subject', 'cab_required' => 1]);
    $made['changes'][] = $id;
    ok('nor edit one', $code(fn() => ChangesService::updateChange($conn, $ctxPlain, $id, ['title' => 'ZZ-RO edited'])) === 'read_only');
    ok('nor comment on it', $code(fn() => ChangesService::createComment($conn, $ctxPlain, $id, ['text' => 'ZZ'])) === 'read_only');
    ok('nor cancel it', $code(fn() => ChangesService::cancelChange($conn, $ctxPlain, $id, [])) === 'read_only');
    ok('nor delete it', $code(fn() => ChangesService::deleteChange($conn, $ctxPlain, $id)) === 'read_only');
    ok('and it is still there', (bool)$conn->query("SELECT 1 FROM changes WHERE id = {$id}")->fetchColumn());
    ChangesService::saveCab($conn, $ctxAdmin, $id, [['analyst_id' => $plain, 'is_required' => true]]);
    ChangesService::updateChange($conn, $ctxAdmin, $id, ['status' => 'Pending Approval']);
    $r = ChangesService::voteCab($conn, $ctxPlain, $id, ['vote' => 'Approve']);
    ok('but named on the CAB, they can still vote - approving is not writing', ($r['vote'] ?? '') === 'Approve');
    ok('POSITIVE CONTROL: an administrator (all modules) is full', analystModuleLevel($conn, $admin, 'changes') === 'full');
    ok('POSITIVE CONTROL: a module with no Read only level is full where granted', analystModuleLevel($conn, $admin, 'tickets') === 'full');

    if ($BASE !== null) {
        $sess = 'zzro' . bin2hex(random_bytes(4));
        $tok = bin2hex(random_bytes(16));
        $name = 'ZZ plain';
        file_put_contents(session_save_path() . '/sess_' . $sess,
            'analyst_id|i:' . $plain . ';analyst_name|s:' . strlen($name) . ':"' . $name . '";csrf_token|s:' . strlen($tok) . ':"' . $tok . '";');
        $http = function (string $path, ?array $json = null) use ($BASE, $sess, $tok) {
            $ch = curl_init($BASE . $path);
            $opt = [CURLOPT_RETURNTRANSFER => true, CURLOPT_COOKIE => 'PHPSESSID=' . $sess,
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'X-CSRF-Token: ' . $tok]];
            if ($json !== null) { $opt[CURLOPT_POST] = true; $opt[CURLOPT_POSTFIELDS] = json_encode($json); }
            curl_setopt_array($ch, $opt);
            $body = (string)curl_exec($ch);
            $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            return [$status, $body];
        };
        [$c, $b] = $http('api/change-management/get.php?id=' . $id);
        ok('HTTP: they can read the change', $c === 200 && str_contains($b, '"success":true'), substr($b, 0, 120));
        [$c, $b] = $http('api/change-management/save.php', ['id' => $id, 'title' => 'ZZ-RO via http']);
        ok('HTTP: a save is refused as read only', $c === 403 && str_contains($b, '"code":"read_only"'), substr($b, 0, 120));
        [$c, $b] = $http('api/change-management/save_comment.php', ['change_id' => $id, 'comment_text' => 'ZZ']);
        ok('HTTP: so is a comment', $c === 403);
        [$c, $b] = $http('change-management/?change_id=' . $id);
        ok('HTTP: the page marks itself read only and offers no New change',
            str_contains($b, 'class="module-read-only"') && !str_contains($b, 'href="new/"'));
        @unlink(session_save_path() . '/sess_' . $sess);
    } else {
        echo "  SKIP  HTTP checks (no base URL)\n";
    }
} catch (Throwable $e) {
    ok('no unexpected exception', false, get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine());
} finally {
    if ($made['grant']) {
        $conn->prepare("DELETE FROM analyst_modules WHERE analyst_id = ? AND module_key = 'changes' AND access_level = 'read'")->execute([$plain]);
    }
    $ids = array_merge($made['changes'], $conn->query("SELECT id FROM changes WHERE title LIKE 'ZZ-RO%'")->fetchAll(PDO::FETCH_COLUMN));
    foreach (array_unique(array_map('intval', $ids)) as $cid) {
        $conn->prepare("UPDATE changes SET approval_id = NULL WHERE id = ? AND title LIKE 'ZZ-%'")->execute([$cid]);
        $conn->prepare("DELETE FROM change_approvals WHERE change_id = ?")->execute([$cid]);
        $conn->prepare("DELETE FROM changes WHERE id = ? AND title LIKE 'ZZ-%'")->execute([$cid]);
    }
    $left = (int)$conn->query("SELECT (SELECT COUNT(*) FROM changes WHERE title LIKE 'ZZ-%')
                                    + (SELECT COUNT(*) FROM analyst_modules WHERE analyst_id = {$plain} AND module_key = 'changes')")->fetchColumn();
    echo "\nTidy: " . ($left === 0 ? 'nothing left behind' : "LEFT BEHIND: {$left} row(s)") . "\n";
}

echo "\n" . str_repeat('-', 70) . "\n  {$pass} passed, {$fail} failed\n";
exit($fail > 0 ? 1 : 0);
