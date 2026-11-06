<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables - it creates analysts, a
   team, a portal user, forms, requests and tickets, and only tidies them up if it
   runs to the end. See tests/README.md. Run it against a disposable install. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Group catalogue approvals (GH #220) - a team decides, N different members approve.
 *
 * Covers what the request asked for, and what must not change:
 *   - a named-person form behaves exactly as before (the upgrade promise);
 *   - the named-person "not your own request" switch, off by default;
 *   - team mode: the requester (a member of staff, matched by email) cannot vote; a
 *     non-member cannot; one person voting twice counts once; a rejection needs a
 *     reason; N approvals decide it and raise ONE ticket;
 *   - rejections_required; a split vote with nobody left is STUCK, never approved;
 *   - membership read at vote time; the policy snapshotted at submit time;
 *   - an administrator's click is not a vote; override needs a reason and is recorded;
 *   - move starts a new round; createVersion() carries the policy;
 *   - two final votes at the same moment raise exactly one ticket (two processes).
 *
 * ⚠️ Touches the database. Everything is prefixed ZZGRP and removed in the finally
 * block. Run:  php tests/catalogue-group-approvals.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/forms.php';
require_once __DIR__ . '/../includes/catalogue_approvals.php';

// ---- A child process for the concurrency check: cast one vote and exit. ----
if (($argv[1] ?? '') === '--vote') {
    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $_SESSION = ['analyst_id' => (int)$argv[3]];
    try {
        $r = catalogueApprovalDecide($conn, (int)$argv[3], (int)$argv[2], 'approved', 'ZZGRP race');
        echo json_encode(['ok' => true, 'decision' => $r['decision']]);
    } catch (Throwable $e) {
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    }
    exit(0);
}

$pass = 0; $fail = 0;
function check(string $label, bool $ok, string $detail = ''): void {
    global $pass, $fail;
    if ($ok) { $pass++; echo "  ok    $label\n"; }
    else     { $fail++; echo "  FAIL  $label" . ($detail !== '' ? "  - $detail" : '') . "\n"; }
}
/** Run $fn and return the exception message it threw, or '' if it threw nothing. */
function refused(callable $fn): string {
    try { $fn(); return ''; } catch (Throwable $e) { return $e->getMessage(); }
}

echo "Group catalogue approvals (GH #220)\n" . str_repeat('=', 64) . "\n";
$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!catalogueTeamApprovalAvailable($conn)) { echo "  SKIPPED - run Database Verification first.\n"; exit(0); }

$made = ['analysts' => [], 'users' => [], 'forms' => [], 'team' => null];
$tag = 'zzgrp' . bin2hex(random_bytes(3));

try {
    $mkAnalyst = function (string $name, bool $admin = false) use ($conn, $tag, &$made) {
        $conn->prepare("INSERT INTO analysts (username, password_hash, full_name, email, is_active, is_admin) VALUES (?, 'x', ?, ?, 1, ?)")
             ->execute([$tag . $name, 'ZZGRP ' . $name, $tag . $name . '@example.invalid', $admin ? 1 : 0]);
        return $made['analysts'][] = (int)$conn->lastInsertId();
    };
    $alice = $mkAnalyst('alice'); $bob = $mkAnalyst('bob'); $carol = $mkAnalyst('carol');
    $rita  = $mkAnalyst('rita');                 // staff, AND the requester through the portal
    $admin = $mkAnalyst('admin', true);
    $conn->prepare("INSERT INTO users (email, display_name, created_at) VALUES (?, 'ZZGRP Rita (portal)', UTC_TIMESTAMP())")
         ->execute([strtoupper($tag) . 'RITA@EXAMPLE.INVALID']);   // same address, different case
    $portalRita = $made['users'][] = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO users (email, display_name, created_at) VALUES (?, 'ZZGRP Uma', UTC_TIMESTAMP())")
         ->execute([$tag . 'uma@example.invalid']);
    $portalUma = $made['users'][] = (int)$conn->lastInsertId();

    $conn->prepare("INSERT INTO teams (name, is_active) VALUES (?, 1)")->execute(['ZZGRP approvers ' . $tag]);
    $team = $made['team'] = (int)$conn->lastInsertId();
    $join = $conn->prepare("INSERT INTO analyst_teams (analyst_id, team_id) VALUES (?, ?)");
    foreach ([$alice, $bob, $rita] as $m) $join->execute([$m, $team]);

    $ctx = new ActorContext(actorId: $admin, source: 'ui');
    $_SESSION = ['analyst_id' => $admin];
    $mkForm = function (array $policy) use ($conn, $ctx, &$made) {
        $id = (int)FormsService::saveForm($conn, $ctx, ['title' => 'ZZGRP form', 'is_portal_visible' => 1, 'requires_approval' => 1,
            'fields' => [['field_type' => 'text', 'label' => 'ZZGRP what']]] + $policy)['id'];
        return $made['forms'][] = $id;
    };
    $fieldOf = fn(int $formId) => (int)$conn->query("SELECT id FROM form_fields WHERE form_id = $formId LIMIT 1")->fetchColumn();
    $submit = fn(int $formId, int $user) => FormsService::submitForm($conn, new ActorContext(actorId: 0, source: 'portal'), $formId, [$fieldOf($formId) => 'ZZGRP thing'], $user);
    $row = fn(int $sid) => $conn->query("SELECT * FROM form_submissions WHERE id = $sid")->fetch(PDO::FETCH_ASSOC);
    $as = function (int $who) { $_SESSION = ['analyst_id' => $who]; };

    // ---- 1. Named person: exactly as before ----
    $f1 = $mkForm(['approver_id' => $alice]);
    $s = $submit($f1, $portalUma);
    $r = $row($s);
    check('named person: the request waits on that person', $r['approval_status'] === 'pending' && (int)$r['approver_id'] === $alice && $r['approval_team_id'] === null);
    $as($bob);
    check('named person: somebody else is refused', str_contains(refused(fn() => catalogueApprovalDecide($conn, $bob, $s, 'approved')), 'assigned approver'));
    $as($alice);
    $res = catalogueApprovalDecide($conn, $alice, $s, 'approved');
    check('named person: one approval decides it and raises the ticket', $res['decision'] === 'approved' && !empty($res['ticket_id']));
    $as($admin);
    $s2 = $submit($f1, $portalUma);
    check('named person: an administrator may still decide it (unchanged)', catalogueApprovalDecide($conn, $admin, $s2, 'rejected')['decision'] === 'rejected');

    // ---- 2. Named person, the requester-exclusion switch ----
    $f2 = $mkForm(['approver_id' => $rita]);
    $s = $submit($f2, $portalRita);
    $as($rita);
    check('switch off (default): the approver may approve their own request, as before', catalogueApprovalDecide($conn, $rita, $s, 'approved')['decision'] === 'approved');
    $as($admin);
    FormsService::saveForm($conn, $ctx, ['id' => $f2, 'approval_exclude_requester' => 1]);
    $s = $submit($f2, $portalRita);
    $as($rita);
    check('switch on: the approver cannot approve their own request', str_contains(refused(fn() => catalogueApprovalDecide($conn, $rita, $s, 'approved')), 'made yourself'));
    check('...and it shows as stuck', catalogueApprovalProgress($conn, $row($s))['stuck_reason'] === 'approver_is_requester');

    // ---- 3. Team mode ----
    $as($admin);
    $f3 = $mkForm(['approval_team_id' => $team, 'approvals_required' => 2, 'rejections_required' => 1]);
    $s = $submit($f3, $portalRita);
    $r = $row($s);
    check('team: the policy is snapshotted onto the request', (int)$r['approval_team_id'] === $team && (int)$r['approvals_required'] === 2 && $r['approver_id'] === null);
    $as($rita);
    check('team: the requester (same email, any case) cannot vote', str_contains(refused(fn() => catalogueApprovalDecide($conn, $rita, $s, 'approved')), 'made yourself'));
    $as($carol);
    check('team: a non-member cannot vote', str_contains(refused(fn() => catalogueApprovalDecide($conn, $carol, $s, 'approved')), 'members'));
    $as($admin);
    check('team: an administrator\'s click is not a vote', str_contains(refused(fn() => catalogueApprovalDecide($conn, $admin, $s, 'approved')), 'members'));
    $as($alice);
    $res = catalogueApprovalDecide($conn, $alice, $s, 'approved', 'fine by me');
    check('team: one approval of two is recorded, still pending', $res['decision'] === 'recorded' && $row($s)['approval_status'] === 'pending' && $res['progress']['approvals'] === 1);
    check('team: the same person again counts once', str_contains(refused(fn() => catalogueApprovalDecide($conn, $alice, $s, 'approved')), 'already decided'));
    $list = catalogueApprovalsList($conn, $bob, 'mine');
    check('inbox: it waits on Bob, showing 1 of 2', in_array($s, array_map('intval', array_column($list['items'], 'id')), true)
        && $list['items'][array_search($s, array_map('intval', array_column($list['items'], 'id')))]['progress']['approvals'] === 1);
    check('inbox: not on Alice, who has voted', !in_array($s, array_map('intval', array_column(catalogueApprovalsList($conn, $alice, 'mine')['items'], 'id')), true));
    check('inbox: Alice sees it under Decided by me', in_array($s, array_map('intval', array_column(catalogueApprovalsList($conn, $alice, 'decided')['items'], 'id')), true));
    // policy snapshot: lowering the form's bar now must not lower this request's
    $as($admin);
    FormsService::saveForm($conn, $ctx, ['id' => $f3, 'approvals_required' => 1]);
    check('team: changing the form does not change a waiting request', (int)$row($s)['approvals_required'] === 2);
    $as($bob);
    $tickets0 = (int)$conn->query("SELECT COUNT(*) FROM tickets")->fetchColumn();
    $res = catalogueApprovalDecide($conn, $bob, $s, 'approved');
    check('team: the second approval decides it and raises one ticket', $res['decision'] === 'approved' && !empty($res['ticket_id'])
        && (int)$conn->query("SELECT COUNT(*) FROM tickets")->fetchColumn() === $tickets0 + 1);
    check('team: both votes are kept as evidence', (int)$conn->query("SELECT COUNT(*) FROM form_submission_votes WHERE submission_id = $s")->fetchColumn() === 2);

    // ---- 4. Rejection ----
    $as($admin);
    FormsService::saveForm($conn, $ctx, ['id' => $f3, 'approvals_required' => 2, 'rejections_required' => 1]);
    $s = $submit($f3, $portalUma);
    $as($alice);
    check('rejecting needs a reason', str_contains(refused(fn() => catalogueApprovalDecide($conn, $alice, $s, 'rejected', '')), 'reason'));
    $res = catalogueApprovalDecide($conn, $alice, $s, 'rejected', 'not this quarter');
    check('one rejection (the default) declines it, no ticket', $res['decision'] === 'rejected' && $row($s)['ticket_id'] === null);

    // ---- 5. Rejections required 2 + a split vote = stuck; then move ----
    $as($admin);
    FormsService::saveForm($conn, $ctx, ['id' => $f3, 'approvals_required' => 2, 'rejections_required' => 2]);
    $s = $submit($f3, $portalRita);   // Rita excluded: Alice and Bob are the only voters
    $as($alice); catalogueApprovalDecide($conn, $alice, $s, 'rejected', 'no');
    $as($bob);   catalogueApprovalDecide($conn, $bob, $s, 'approved');
    $p = catalogueApprovalProgress($conn, $row($s));
    check('a split vote with nobody left is stuck, still pending', $p['stuck'] && $row($s)['approval_status'] === 'pending', json_encode($p['stuck_reason']));
    $as($bob);
    check('only an administrator can move it', str_contains(refused(fn() => catalogueApprovalMove($conn, $bob, $s, ['approver_id' => $carol], 'x')), 'administrator'));
    $as($admin);
    check('a move needs a reason', str_contains(refused(fn() => catalogueApprovalMove($conn, $admin, $s, ['approver_id' => $carol], '')), 'reason'));
    catalogueApprovalMove($conn, $admin, $s, ['approver_id' => $carol], 'team split - Carol to decide');
    $r = $row($s);
    check('moved: a new round, now waiting on Carol', (int)$r['approval_round'] === 2 && (int)$r['approver_id'] === $carol && $r['approval_team_id'] === null);
    check('moved: the move is recorded with its reason', (string)$conn->query("SELECT reason FROM form_submission_approval_events WHERE submission_id = $s AND action = 'moved'")->fetchColumn() === 'team split - Carol to decide');
    $as($carol);
    check('moved: Carol decides it', catalogueApprovalDecide($conn, $carol, $s, 'approved')['decision'] === 'approved');

    // ---- 6. Membership is read at vote time ----
    $as($admin);
    FormsService::saveForm($conn, $ctx, ['id' => $f3, 'approvals_required' => 2, 'rejections_required' => 1]);
    $s = $submit($f3, $portalUma);
    $conn->prepare("DELETE FROM analyst_teams WHERE analyst_id = ? AND team_id = ?")->execute([$bob, $team]);
    $join->execute([$carol, $team]);
    $as($bob);
    check('someone who has left the team cannot vote', str_contains(refused(fn() => catalogueApprovalDecide($conn, $bob, $s, 'approved')), 'members'));
    $as($carol);
    check('someone who has joined can', catalogueApprovalDecide($conn, $carol, $s, 'approved')['decision'] === 'recorded');

    // ---- 7. Override ----
    $as($alice);
    check('only an administrator can override', str_contains(refused(fn() => catalogueApprovalOverride($conn, $alice, $s, 'approved', 'x')), 'administrator'));
    $as($admin);
    check('an override needs a reason', str_contains(refused(fn() => catalogueApprovalOverride($conn, $admin, $s, 'approved', '')), 'reason'));
    $res = catalogueApprovalOverride($conn, $admin, $s, 'approved', 'urgent - CFO request');
    check('override decides it, recorded as an override, not as votes', $res['decision'] === 'approved'
        && (int)$conn->query("SELECT COUNT(*) FROM form_submission_votes WHERE submission_id = $s")->fetchColumn() === 1
        && (int)$conn->query("SELECT COUNT(*) FROM form_submission_approval_events WHERE submission_id = $s AND action = 'override_approved'")->fetchColumn() === 1);

    // ---- 8. createVersion() carries the policy ----
    $v = FormsService::createVersion($conn, $ctx, $f3);
    $newId = (int)($v['id'] ?? $v['new_id'] ?? 0);
    if ($newId) $made['forms'][] = $newId;
    $nf = $conn->query("SELECT approval_team_id, approvals_required, rejections_required FROM forms WHERE id = $newId")->fetch(PDO::FETCH_ASSOC);
    check('a new version keeps the team, approvals and rejections', $nf && (int)$nf['approval_team_id'] === $team && (int)$nf['approvals_required'] === 2, json_encode($nf));

    // ---- 9. Two final votes at the same moment ----
    $join->execute([$bob, $team]);
    $s = $submit($f3, $portalUma);
    $before = (int)$conn->query("SELECT COUNT(*) FROM tickets")->fetchColumn();
    $procs = [];
    foreach ([$alice, $bob] as $who) {
        $procs[] = proc_open([PHP_BINARY, __FILE__, '--vote', (string)$s, (string)$who], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $outs[] = $pipes[1];
    }
    $results = array_map(fn($p) => json_decode((string)stream_get_contents($p), true), $outs);
    foreach ($procs as $p) proc_close($p);
    $decisions = array_column($results, 'decision');
    sort($decisions);
    check('two votes at once: both counted, the request approved once', $decisions === ['approved', 'recorded'] && $row($s)['approval_status'] === 'approved', json_encode($results));
    check('...and exactly one ticket raised', (int)$conn->query("SELECT COUNT(*) FROM tickets")->fetchColumn() === $before + 1);

    // ---- 10. An empty team is stuck, never waved through ----
    $conn->prepare("DELETE FROM analyst_teams WHERE team_id = ?")->execute([$team]);
    $s = $submit($f3, $portalUma);
    $p = catalogueApprovalProgress($conn, $row($s));
    check('an empty team: stuck, still pending', $p['stuck'] && $row($s)['approval_status'] === 'pending', (string)$p['stuck_reason']);

    // ---- 11. A form from before the upgrade (no team keys) gates as before ----
    $g = catalogueApprovalGate(['requires_approval' => 1, 'approver_id' => $alice], $portalUma);
    check('a pre-upgrade form row gates on its named approver', $g['approver_id'] === $alice && $g['approval_team_id'] === null && $g['approval_exclude_requester'] === 0);
} finally {
    // Tickets the test raised, then everything else it made.
    foreach ($made['forms'] as $fid) {
        foreach ($conn->query("SELECT ticket_id FROM form_submissions WHERE form_id = " . (int)$fid . " AND ticket_id IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN) as $tid) {
            foreach (['emails', 'ticket_audit'] as $tb) { try { $conn->prepare("DELETE FROM $tb WHERE ticket_id = ?")->execute([$tid]); } catch (Throwable $e) {} }
            $conn->prepare("UPDATE form_submissions SET ticket_id = NULL WHERE ticket_id = ?")->execute([$tid]);
            try { $conn->prepare("DELETE FROM tickets WHERE id = ?")->execute([$tid]); } catch (Throwable $e) {}
        }
        $conn->prepare("DELETE sd FROM form_submission_data sd JOIN form_submissions s ON s.id = sd.submission_id WHERE s.form_id = ?")->execute([$fid]);
        $conn->prepare("DELETE FROM form_submissions WHERE form_id = ?")->execute([$fid]);
    }
    foreach (array_reverse($made['forms']) as $fid) {
        $conn->prepare("DELETE FROM form_fields WHERE form_id = ?")->execute([$fid]);
        try { $conn->prepare("DELETE FROM forms WHERE id = ?")->execute([$fid]); } catch (Throwable $e) {}
    }
    if ($made['team']) {
        $conn->prepare("DELETE FROM analyst_teams WHERE team_id = ?")->execute([$made['team']]);
        $conn->prepare("DELETE FROM teams WHERE id = ?")->execute([$made['team']]);
    }
    foreach ($made['users'] as $uid) $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$uid]);
    foreach ($made['analysts'] as $aid) { try { $conn->prepare("DELETE FROM analysts WHERE id = ?")->execute([$aid]); } catch (Throwable $e) {} }
    $left = (int)$conn->query("SELECT COUNT(*) FROM forms WHERE title = 'ZZGRP form'")->fetchColumn()
          + (int)$conn->query("SELECT COUNT(*) FROM analysts WHERE username LIKE '$tag%'")->fetchColumn();
    check('cleanup removed everything it made', $left === 0, "$left left");
    echo str_repeat('-', 64) . "\n$pass passed, $fail failed\n";
    exit($fail ? 1 : 0);
}
