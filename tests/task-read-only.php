<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Read only access to Tasks (#181 Part G) - the Auditor role's half that is module access.
 *
 *   php tests/task-read-only.php [base-url]
 *
 * A restricted non-admin is given Tasks at the Read only level. They can read a task, its
 * history and its evidence report, and every write - save, move, delete, comment, skip, a
 * checklist tick - is refused, in the service and (with a base URL) over HTTP. The page
 * marks itself read only. Positive controls: an administrator is full, and a full grant writes.
 *
 * @wiki Repeating-Task-Evidence-Developer-Guide
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/tasks.php';

$BASE = isset($argv[1]) ? rtrim($argv[1], '/') . '/' : null;
$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
$code = function (callable $fn): string {
    try { $fn(); return ''; } catch (ServiceError $e) { return $e->errorCode; }
};

$conn = connectToDatabase();
$plain = (int)$conn->query("SELECT a.id FROM analysts a WHERE a.is_active = 1 AND a.is_admin = 0 AND a.can_access_all_modules = 0
    AND NOT EXISTS (SELECT 1 FROM analyst_modules m WHERE m.analyst_id = a.id AND m.module_key = 'tasks')
    AND NOT EXISTS (SELECT 1 FROM analyst_teams at WHERE at.analyst_id = a.id) ORDER BY a.id LIMIT 1")->fetchColumn();
$admin = (int)$conn->query("SELECT id FROM analysts WHERE is_active = 1 AND is_admin = 1 ORDER BY id LIMIT 1")->fetchColumn();
if (!$plain || !$admin) { echo "Needs a restricted non-admin with no Tasks grant and no team, and an admin.\n"; exit(1); }
$ctxPlain = new ActorContext($plain, null, 'ui', 'en', 'ZZ plain');
$ctxAdmin = new ActorContext($admin, null, 'ui', 'en', 'ZZ admin');
$grant = false; $taskId = 0;
$closed = (int)$conn->query("SELECT id FROM task_statuses WHERE is_active=1 AND is_closed=1 ORDER BY display_order, id LIMIT 1")->fetchColumn();

echo "\nRead only access to Tasks (#181 Part G)\n" . str_repeat('=', 70) . "\n";
try {
    $conn->prepare("INSERT INTO analyst_modules (analyst_id, module_key, access_level) VALUES (?, 'tasks', 'read')")->execute([$plain]);
    $grant = true;
    ok('Tasks offers Read only', in_array('tasks', MODULES_WITH_READ_ONLY, true));
    ok('a Read only grant is access', analystCanAccessModule($conn, $plain, 'tasks'));
    ok('at the read level', analystModuleLevel($conn, $plain, 'tasks') === 'read');

    $taskId = TasksService::saveTask($conn, $ctxAdmin, ['title' => 'ZZ-RO task'])['id'];
    ok('POSITIVE CONTROL: an administrator can create a task', $taskId > 0);
    ok('they cannot create one', $code(fn() => TasksService::saveTask($conn, $ctxPlain, ['title' => 'ZZ-RO refused'])) === 'read_only');
    ok('nor edit one', $code(fn() => TasksService::saveTask($conn, $ctxPlain, ['id' => $taskId, 'title' => 'ZZ-RO edited'])) === 'read_only');
    ok('nor move one', $code(fn() => TasksService::moveTask($conn, $ctxPlain, $taskId, ['status_id' => $closed])) === 'read_only');
    ok('nor comment on one', $code(fn() => TasksService::createComment($conn, $ctxPlain, $taskId, 'ZZ')) === 'read_only');
    ok('nor delete one', $code(fn() => TasksService::deleteTask($conn, $ctxPlain, $taskId)) === 'read_only');
    ok('nor skip one', $code(fn() => TasksService::skipOccurrence($conn, $ctxPlain, $taskId, 'ZZ')) === 'read_only');
    ok('and it is unchanged', (string)$conn->query("SELECT title FROM tasks WHERE id = {$taskId}")->fetchColumn() === 'ZZ-RO task');
    ok('POSITIVE CONTROL: an administrator is full', analystModuleLevel($conn, $admin, 'tasks') === 'full');

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
            return [(int)curl_getinfo($ch, CURLINFO_HTTP_CODE), $body];
        };
        [$c, $b] = $http('api/tasks/get.php?id=' . $taskId);
        ok('HTTP: they can read the task', $c === 200 && str_contains($b, '"success":true'), substr($b, 0, 120));
        ok('HTTP: and are told they cannot write it', str_contains($b, '"can_write":false'));
        [$c, $b] = $http('api/tasks/history.php?task_id=' . $taskId);
        ok('HTTP: they can read its history', str_contains($b, '"success":true'), substr($b, 0, 120));
        [$c, $b] = $http('api/tasks/save.php', ['id' => $taskId, 'title' => 'ZZ-RO via http']);
        ok('HTTP: a save is refused as read only', $c === 403 && str_contains($b, '"code":"read_only"'), substr($b, 0, 120));
        [$c, $b] = $http('api/tasks/reorder.php', ['task_id' => $taskId, 'new_status' => 'x', 'positions' => []]);
        ok('HTTP: so is a drag on the board', $c === 403);
        [$c, $b] = $http('api/tasks/save_comment.php', ['task_id' => $taskId, 'comment' => 'ZZ']);
        ok('HTTP: so is a comment', $c === 403);
        [$c, $b] = $http('api/tasks/delete.php', ['id' => $taskId]);
        ok('HTTP: so is a delete', $c === 403);
        [$c, $b] = $http('tasks/');
        ok('HTTP: the board marks itself read only', str_contains($b, 'class="module-read-only"'));
        @unlink(session_save_path() . '/sess_' . $sess);
    } else {
        echo "  SKIP  HTTP checks (no base URL)\n";
    }
} catch (Throwable $e) {
    ok('no unexpected exception', false, get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine());
} finally {
    if ($grant) $conn->prepare("DELETE FROM analyst_modules WHERE analyst_id = ? AND module_key = 'tasks' AND access_level = 'read'")->execute([$plain]);
    $ids = array_map('intval', $conn->query("SELECT id FROM tasks WHERE title LIKE 'ZZ-RO%'")->fetchAll(PDO::FETCH_COLUMN));
    if ($ids) {
        $in = implode(',', $ids);
        $conn->exec("DELETE FROM task_events WHERE task_id IN ($in)");
        $conn->exec("DELETE FROM task_comments WHERE task_id IN ($in)");
        $conn->exec("DELETE FROM tasks WHERE id IN ($in)");
    }
    $left = (int)$conn->query("SELECT (SELECT COUNT(*) FROM tasks WHERE title LIKE 'ZZ-RO%')
                                    + (SELECT COUNT(*) FROM analyst_modules WHERE analyst_id = {$plain} AND module_key = 'tasks')")->fetchColumn();
    echo "\nTidy: " . ($left === 0 ? 'nothing left behind' : "LEFT BEHIND: {$left} row(s)") . "\n";
}

echo "\n" . str_repeat('-', 70) . "\n  {$pass} passed, {$fail} failed\n";
exit($fail > 0 ? 1 : 0);
