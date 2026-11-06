<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * The case report (GH #204): one ticket and its tasks as a printable record.
 *
 *   php tests/case-report.php
 *
 * Why it exists: the report is evidence that leaves the building, so the things that matter
 * are what it must NOT do - run a customer's markup, show internal notes nobody chose to
 * include, name a task the reader may not open - and that what it leaves out is COUNTED,
 * not silently dropped. The first build lost every such count: the reads that made them ran
 * in arrow functions, which take the report by value, so "0 left out" printed whatever was
 * left out. The internal-notes check below is the one that caught it.
 *
 * Runs against the real database inside ONE transaction that is rolled back at the end, so
 * nothing it makes - a ZZ ticket, its messages and notes, a task with a checklist and a
 * subtask, a restricted ZZ analyst - outlives the run. Control: the internal note must
 * APPEAR when it is ticked in, or "absent when not ticked" proves nothing.
 */

chdir(__DIR__ . '/..');
$_SESSION = ['analyst_id' => 1, 'analyst_name' => 'ZZ Case Report Test'];
$_SERVER['HTTP_HOST'] = 'localhost';
require_once 'config.php';
require_once 'includes/functions.php';
require_once 'includes/i18n.php';
require_once 'includes/timezone.php';
require_once 'includes/rbac.php';
require_once 'includes/case_report.php';
require_once 'includes/case_report_page.php';
I18n::initFromSession();
Tz::init();

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

/** The page HTML for a report. */
function render(?array $r): string {
    ob_start();
    caseReportPage($r, false, null);
    return (string)ob_get_clean();
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->beginTransaction();
try {
    $ins = function (string $sql, array $a) use ($conn): int { $conn->prepare($sql)->execute($a); return (int)$conn->lastInsertId(); };

    $evil = '<script>alert("zz-xss")</script><img src=x onerror="alert(1)">';
    $ticketId = $ins("INSERT INTO tickets (ticket_number, subject, created_datetime, updated_datetime) VALUES (?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())",
                     ['ZZ-CR-' . bin2hex(random_bytes(3)), 'ZZ case <b>subject</b> ' . $evil]);
    $ins("INSERT INTO emails (ticket_id, subject, from_name, from_address, received_datetime, body_content, body_type, direction)
          VALUES (?, 'ZZ hello', 'ZZ Customer', 'zz@example.com', UTC_TIMESTAMP(), ?, 'html', 'Inbound')",
         [$ticketId, '<p>ZZ message body visible</p>' . $evil . '<a href="javascript:alert(2)">zz link</a>']);
    $ins("INSERT INTO ticket_notes (ticket_id, analyst_id, note_text, is_internal) VALUES (?, 1, 'ZZ shared note text', 0)", [$ticketId]);
    $ins("INSERT INTO ticket_notes (ticket_id, analyst_id, note_text, is_internal) VALUES (?, 1, 'ZZ internal secret note', 1)", [$ticketId]);

    $taskId = $ins("INSERT INTO tasks (title, description, ticket_id, created_by_id) VALUES ('ZZ parent task', ?, ?, 1)", ['<p>ZZ task description</p>' . $evil, $ticketId]);
    $subId  = $ins("INSERT INTO tasks (title, parent_task_id, ticket_id, created_by_id) VALUES ('ZZ subtask', ?, ?, 1)", [$taskId, $ticketId]);
    $ins("INSERT INTO task_comments (task_id, analyst_id, comment) VALUES (?, 1, ?)", [$taskId, '<p>ZZ comment</p>' . $evil]);
    $listId = $ins("INSERT INTO task_checklists (task_id, title) VALUES (?, 'ZZ checklist')", [$taskId]);
    $itemA = $ins("INSERT INTO task_checklist_items (task_checklist_id, title, is_mandatory, is_completed, result, completed_by_name, completed_datetime)
                   VALUES (?, 'ZZ step passes', 1, 1, 'pass', 'ZZ Analyst', UTC_TIMESTAMP())", [$listId]);
    $ins("INSERT INTO task_checklist_items (task_checklist_id, title, is_mandatory, is_completed, result, exception_note)
          VALUES (?, 'ZZ step fails', 1, 1, 'fail', 'ZZ exception noted')", [$listId]);
    $ins("INSERT INTO task_checklist_item_events (task_id, task_checklist_id, item_id, checklist_title, item_title, action, after_json, actor_name, created_datetime)
          VALUES (?, ?, ?, 'ZZ checklist', 'ZZ step passes', 'result', '{\"result\":\"pass\"}', 'ZZ Analyst', UTC_TIMESTAMP())", [$taskId, $listId, $itemA]);

    echo "Ticket report, as an administrator\n";
    $all = CaseReport::options(['set' => 1, 's' => CaseReport::SECTIONS]);
    $r = CaseReport::forTicket($conn, $ticketId, 1, $all);
    $html = render($r);
    ok('it is found', $r !== null);
    ok('no section was unreadable', $r && !$r['unavailable'], json_encode($r['unavailable'] ?? null));
    ok('the message body is there', str_contains($html, 'ZZ message body visible'));
    ok('no script survives anywhere', !str_contains($html, '<script>alert'));
    // A live tag only: the escaped subject legitimately shows the text "onerror=" inside &lt;...&gt;.
    // The PAYLOAD as a live tag. Not "any <img>" or "any on...=": the page's own logo is an
    // image and its choices panel submits on change - those are the page, not the record.
    $liveTag = '/<img\b[^>]*\bsrc=["\']?x\b|<[^>]*\bonerror\s*=/i';
    ok('control: the live-tag pattern catches a live tag', preg_match($liveTag, '<p><img src=x onerror="a()"></p>') === 1
       && preg_match($liveTag, '&lt;img src=x onerror=&quot;a&quot;&gt;') === 0);
    ok('no live image tag or event handler survives', !preg_match($liveTag, $html));
    ok('no javascript: link survives', !str_contains($html, 'javascript:alert'));
    ok('the subject is escaped, not rendered', str_contains($html, '&lt;b&gt;subject&lt;/b&gt;'));
    ok('the shared note is there', str_contains($html, 'ZZ shared note text'));
    ok('the internal note is NOT there by default', !str_contains($html, 'ZZ internal secret note'));
    ok('...and it is counted as left out', ($r['hidden']['internal_notes'] ?? 0) === 1, (string)($r['hidden']['internal_notes'] ?? 'null'));
    ok('...and the report says so', str_contains($html, 'Internal notes are not included (1 left out)'));
    ok('the task, its checklist results and exception are there',
       str_contains($html, 'ZZ parent task') && str_contains($html, 'ZZ step fails') && str_contains($html, 'ZZ exception noted') && str_contains($html, 'res-fail'));
    ok('the subtask is inside the report', str_contains($html, 'ZZ subtask'));
    ok('the step change is in the task history', str_contains($html, 'ZZ step passes: result Pass'));
    ok('times say which zone they are in', str_contains($html, 'All times are in ' . date_default_timezone_get()));

    echo "Control: the internal note when it is ticked in\n";
    $withInternal = render(CaseReport::forTicket($conn, $ticketId, 1, CaseReport::options(['set' => 1, 's' => CaseReport::SECTIONS, 'internal' => 1])));
    ok('it appears', str_contains($withInternal, 'ZZ internal secret note'));

    echo "A reader without Tasks\n";
    $zz = $ins("INSERT INTO analysts (username, password_hash, full_name, email, can_access_all_modules) VALUES (?, 'x', 'ZZ No Tasks', 'zz-nt@example.invalid', 0)",
               ['zz_cr_' . bin2hex(random_bytes(3))]);
    $ins("INSERT INTO analyst_modules (analyst_id, module_key) VALUES (?, 'tickets')", [$zz]);
    $rn = CaseReport::forTicket($conn, $ticketId, $zz, $all);
    $hn = render($rn);
    ok('the ticket is still reported', $rn !== null && str_contains($hn, 'ZZ message body visible'));
    ok('no task is named', !str_contains($hn, 'ZZ parent task') && !str_contains($hn, 'ZZ subtask'));
    ok('the report says why the tasks are missing', str_contains($hn, 'you do not have access to Tasks'));
    ok('a task-only report is refused for them', CaseReport::forTask($conn, $taskId, $zz, $all) === null);

    echo "Choosing sections\n";
    $only = render(CaseReport::forTicket($conn, $ticketId, 1, CaseReport::options(['set' => 1, 's' => ['history']])));
    ok('a section left out is left out', !str_contains($only, 'ZZ message body visible') && !str_contains($only, 'ZZ parent task'));
    ok('and the report names what was left out', str_contains($only, 'Left out by choice:'));

    echo "Printing records the export\n";
    CaseReport::recordExport($conn, $r, 1);
    $st = $conn->prepare("SELECT new_value FROM ticket_audit WHERE ticket_id = ? AND field_name = 'Case report'");
    $st->execute([$ticketId]);
    $rec = (string)$st->fetchColumn();
    ok('a Case report line is on the ticket\'s audit trail, with the scope', str_contains($rec, 'Exported by ZZ Case Report Test') && str_contains($rec, 'conversation'));

    echo "A task on its own\n";
    $rt = CaseReport::forTask($conn, $taskId, 1, $all);
    $ht = render($rt);
    ok('it is reported with its subtask', $rt !== null && str_contains($ht, 'ZZ parent task') && str_contains($ht, 'ZZ subtask'));
    ok('the task description is cleaned too', str_contains($ht, 'ZZ task description') && !str_contains($ht, '<script>alert'));
} finally {
    $conn->rollBack();
}

echo "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
