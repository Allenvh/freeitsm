<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Ticket folder counts for an analyst in a team (3.7.0).
 *
 *   php tests/team-counts-no-departments.php
 *
 * Someone in a team gets ticket folders and counts for that team's departments,
 * PLUS every ticket with no department. Until 3.7.0 a team linked to NO
 * department short-circuited every count to 0 instead - All Tickets, every
 * status, every other analyst's folder - so an install with no departments yet
 * showed 0 everywhere the moment somebody joined a team. Wiki:
 * Team-With-No-Departments-Showed-Zero-Tickets.
 *
 * Drives the REAL endpoint (api/tickets/get_ticket_counts.php) in-process as
 * one analyst who is in no team, in three setups:
 *
 *   1. no team                     - the control, the numbers to compare with
 *   2. a team with NO departments  - All Tickets must equal the tickets with no
 *                                    department (the bug said 0)
 *   3. a team with ONE department  - only that department plus no-department
 *                                    tickets; a department deliberately left
 *                                    unlinked must stay out
 *
 * The endpoint opens its own database connection, so a rolled-back transaction
 * is invisible to it. The test therefore writes ONE throwaway team ("ZZ test
 * ...") and its links, and deletes them by id in a finally block, then checks
 * they are gone. It writes nothing else.
 *
 * Control run against the old code: COUNTS_ENDPOINT=<path to old copy> php tests/...
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';

$pass = 0; $fail = 0;
/**
 * Records and prints one PASS or FAIL line.
 *
 * @param string $what  what is being checked, in a few words
 * @param bool $cond  true when the check passed
 * @param string $detail  shown after a failure - the values that disagreed
 */
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

/**
 * Runs the counts endpoint in this process as the analyst in $_SESSION and
 * returns its decoded answer. From its own folder, so its relative requires
 * resolve; inside a function, so its variables stay out of this file's.
 *
 * @param string $endpoint  absolute path to get_ticket_counts.php (or an old copy, for the control run)
 */
function runCounts(string $endpoint): array {
    $cwd = getcwd();
    // On the command line, after this script has printed, the endpoint's
    // session_start() and header() can only warn - $_SESSION as set below is
    // what it reads. Swallow exactly those two warnings; anything else shows.
    set_error_handler(function ($no, $msg) {
        return stripos($msg, 'session') !== false || stripos($msg, 'headers already sent') !== false;
    });
    ob_start();
    try {
        chdir(dirname($endpoint));
        include $endpoint;
    } finally {
        $raw = (string)ob_get_clean();
        chdir($cwd);
        restore_error_handler();
    }
    $d = json_decode(trim($raw), true);
    if (!is_array($d) || empty($d['success'])) {
        echo "  FAIL  the endpoint did not answer: " . substr(trim($raw), 0, 300) . "\n";
        exit(1);
    }
    return $d;
}

/**
 * The count on one analyst's folder in an endpoint answer, or 0 if absent.
 *
 * @param array $d  a decoded counts answer
 * @param int $analystId  whose folder
 */
function ownCount(array $d, int $analystId): int {
    foreach ($d['analysts'] as $a) if ((int)$a['id'] === $analystId) return (int)$a['count'];
    return 0;
}

echo "\nTicket folder counts for a team with no departments (3.7.0)\n" . str_repeat('=', 70) . "\n";
$endpoint = getenv('COUNTS_ENDPOINT') ?: realpath(__DIR__ . '/../api/tickets/get_ticket_counts.php');
$conn = connectToDatabase();

// An active analyst in no team, with the Tickets module - the endpoint refuses anyone else.
$analystId = 0;
foreach ($conn->query("SELECT a.id FROM analysts a WHERE a.is_active = 1
                        AND NOT EXISTS (SELECT 1 FROM analyst_teams ant WHERE ant.analyst_id = a.id)
                        ORDER BY a.id")->fetchAll(PDO::FETCH_COLUMN) as $id) {
    if (analystCanAccessModule($conn, (int)$id, 'tickets')) { $analystId = (int)$id; break; }
}
if (!$analystId) { echo "  SKIP  no active analyst outside every team with Tickets access\n"; exit(0); }

// No real session: the endpoint reads these, and its own session_start() cannot
// replace them once output has begun (see runCounts()).
$_SESSION = [];
$_SESSION['analyst_id'] = $analystId;
$_SESSION['active_tenant_id'] = getDefaultTenantId($conn);
echo "  (as analyst #{$analystId}, " . basename(dirname($endpoint)) . '/' . basename($endpoint) . ")\n";

$teamId = 0; $deptId = 0;
try {
    // ---- 1. no team: the control ------------------------------------------
    $base = runCounts($endpoint);
    $noDept = (int)$base['unassigned_count'];   // tickets with no department - never team-filtered
    $own    = ownCount($base, $analystId);
    ok("positive control: there are tickets with no department ({$noDept})", $noDept > 0,
       'nothing to prove - every check below would pass vacuously');

    // ---- 2. a team with NO departments --------------------------------------
    $conn->prepare("INSERT INTO teams (name, description) VALUES ('ZZ test team counts', 'temporary - tests/team-counts-no-departments.php')")->execute();
    $teamId = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO analyst_teams (analyst_id, team_id) VALUES (?, ?)")->execute([$analystId, $teamId]);

    $none = runCounts($endpoint);
    ok("no departments: All Tickets counts the tickets with no department",
       (int)$none['total_count'] === $noDept, "All Tickets {$none['total_count']}, no-department tickets {$noDept}");
    ok("no departments: the status folders are counted too, not left at 0",
       array_sum($none['overall_statuses']) > 0 && array_sum($none['overall_statuses']) <= (int)$none['total_count'],
       'statuses ' . array_sum($none['overall_statuses']) . ", All Tickets {$none['total_count']}");
    ok("no departments: no department folders", count($none['departments']) === 0);
    ok("no departments: their own folder is unchanged", ownCount($none, $analystId) === $own,
       'own ' . ownCount($none, $analystId) . ", before {$own}");

    // ---- 3. a team with ONE department ---------------------------------------
    // The busiest department, so its count proves something; another must exist with
    // tickets, or "left out" cannot be seen.
    $byDept = [];
    foreach ($base['departments'] as $d) $byDept[(int)$d['id']] = (int)$d['count'];
    arsort($byDept);
    $deptId = (int)array_key_first($byDept);
    $others = array_sum($byDept) - ($byDept[$deptId] ?? 0);
    ok("positive control: tickets sit in a department the team is NOT linked to ({$others})", $deptId > 0 && $others > 0,
       'cannot show a deliberately unlinked department staying hidden');
    if ($deptId > 0) {
        $conn->prepare("INSERT INTO department_teams (department_id, team_id) VALUES (?, ?)")->execute([$deptId, $teamId]);
        $one = runCounts($endpoint);
        $expect = $noDept + $byDept[$deptId];
        ok("one department: All Tickets = that department + no-department tickets ({$expect})",
           (int)$one['total_count'] === $expect, "All Tickets {$one['total_count']}");
        ok("one department: the unlinked departments stay out of All Tickets",
           (int)$one['total_count'] < (int)$base['total_count'], "All Tickets {$one['total_count']}, with no team {$base['total_count']}");
        ok("one department: only that department's folder is offered",
           count($one['departments']) === 1 && (int)$one['departments'][0]['id'] === $deptId);
    }
} finally {
    if ($teamId) {
        $conn->prepare("DELETE FROM analyst_teams WHERE team_id = ? AND analyst_id = ?")->execute([$teamId, $analystId]);
        $conn->prepare("DELETE FROM department_teams WHERE team_id = ?")->execute([$teamId]);
        $conn->prepare("DELETE FROM teams WHERE id = ? AND name = 'ZZ test team counts'")->execute([$teamId]);
    }
}
$left = (int)$conn->query("SELECT COUNT(*) FROM teams WHERE name = 'ZZ test team counts'")->fetchColumn()
      + (int)$conn->query("SELECT COUNT(*) FROM analyst_teams WHERE analyst_id = $analystId")->fetchColumn();
ok('cleanup: the throwaway team and its links are gone', $left === 0, "{$left} row(s) left");

echo "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
