<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Change approvals (#180) - status stages, the approval date, and votes that start
 * again on resubmission.
 *
 *   php tests/change-approvals.php
 *
 * Runs against the real database. Everything it creates is named ZZ- and is removed
 * in the finally block, whatever happens: changes (their audit, CAB rows and links go
 * with them by cascade) and any ZZ status. It never edits an existing status or change.
 *
 * Every refusal is paired with a positive control, so a rule that refused everything
 * could not pass for a working one.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/changes.php';
require_once __DIR__ . '/../includes/changes/approvals.php';

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

$conn = connectToDatabase();
$made = ['changes' => [], 'statuses' => [], 'types' => [], 'templates' => []];

/** Reads one change row with its status name and stage. */
function zzChange(PDO $conn, int $id): array {
    $s = $conn->prepare("SELECT c.*, cs.name AS status_name, cs.stage AS status_stage
                           FROM changes c LEFT JOIN change_statuses cs ON cs.id = c.status_id WHERE c.id = ?");
    $s->execute([$id]);
    return $s->fetch(PDO::FETCH_ASSOC) ?: [];
}

// Approval settings the tests switch: the value before is kept and put back.
$zzSaved = [];
/**
 * Sets one system setting for the test, remembering what was there.
 *
 * @param string $key  the setting key
 * @param string $value  the value to set
 */
function zzSetting(PDO $conn, string $key, string $value): void {
    global $zzSaved;
    if (!array_key_exists($key, $zzSaved)) {
        $s = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
        $s->execute([$key]);
        $old = $s->fetchColumn();
        $zzSaved[$key] = $old === false ? null : $old;
    }
    $conn->prepare("DELETE FROM system_settings WHERE setting_key = ?")->execute([$key]);
    $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)")->execute([$key, $value]);
}
/** Puts back every setting zzSetting() changed. */
function zzSettingRestore(PDO $conn): void {
    global $zzSaved;
    foreach ($zzSaved as $key => $old) {
        $conn->prepare("DELETE FROM system_settings WHERE setting_key = ?")->execute([$key]);
        if ($old !== null) {
            $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)")->execute([$key, $old]);
        }
    }
    $zzSaved = [];
}

// Two active analysts to act as voters. Read, never changed.
$voters = $conn->query("SELECT id FROM analysts WHERE is_active = 1 ORDER BY id LIMIT 2")->fetchAll(PDO::FETCH_COLUMN);
if (count($voters) < 2) { echo "Needs two active analysts.\n"; exit(1); }
[$a1, $a2] = array_map('intval', $voters);
$ctx1 = new ActorContext($a1, null, 'ui', 'en', 'ZZ test 1');
$ctx2 = new ActorContext($a2, null, 'ui', 'en', 'ZZ test 2');

echo "\nChange approvals (#180)\n" . str_repeat('=', 70) . "\n";

try {
    // ── Slice A: stages ─────────────────────────────────────────────────────
    echo "\nStatus stages\n";
    $unclassified = $conn->query("SELECT name FROM change_statuses WHERE stage IS NULL AND name IN ('"
        . implode("','", array_keys(CHANGE_STAGE_SEED_NAMES)) . "')")->fetchAll(PDO::FETCH_COLUMN);
    ok('every seeded status name has its stage', $unclassified === [], implode(', ', $unclassified));
    ok('each required stage has an active status', changeStagesWithoutActiveStatus($conn) === []);
    ok('the backfill is idempotent (a second run classifies nothing)', changeStagesBackfill($conn) === 0);
    ok('POSITIVE CONTROL: changeStatusForStage finds an approved status', changeStatusForStage($conn, 'approved') !== null);

    // A ZZ status in the approved stage, ordered first, stands in for "Approved renamed
    // to Authorised": if the code still went by name, a vote would not land on it (F3).
    $conn->prepare("INSERT INTO change_statuses (name, stage, is_closed, colour, is_default, display_order, is_active)
                    VALUES ('ZZ Authorised', 'approved', 0, '#000000', 0, -1000, 1)")->execute();
    $zzStatus = (int)$conn->lastInsertId();
    $made['statuses'][] = $zzStatus;
    ok('the first approved-stage status is now the ZZ one', (changeStatusForStage($conn, 'approved')['id'] ?? 0) === $zzStatus);

    // ── F1: an approval records its date ────────────────────────────────────
    echo "\nF1 - an approval records when it happened\n";
    $id = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-F1 manual approval', 'status' => 'Draft']);
    $made['changes'][] = $id;
    ok('POSITIVE CONTROL: a new draft has no approval date', zzChange($conn, $id)['approval_datetime'] === null);
    ChangesService::updateChange($conn, $ctx1, $id, ['status' => 'Approved']);
    $first = zzChange($conn, $id)['approval_datetime'];
    ok('moving it to Approved by hand stamps approval_datetime', $first !== null);
    ChangesService::updateChange($conn, $ctx1, $id, ['status' => 'In Progress']);
    ChangesService::updateChange($conn, $ctx1, $id, ['status' => 'Approved']);
    ok('back from In Progress to Approved keeps the original date', zzChange($conn, $id)['approval_datetime'] === $first);

    $id2 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-F1 created approved', 'status' => 'Approved']);
    $made['changes'][] = $id2;
    ok('a change created straight into Approved is stamped too', zzChange($conn, $id2)['approval_datetime'] !== null);

    // ── F2 + F3: a rejection, a resubmission, then an approval ──────────────
    echo "\nF2 - a resubmission starts the vote again; F3 - the vote lands by stage\n";
    $id3 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-F2 resubmit', 'status' => 'Draft', 'cab_required' => 1]);
    $made['changes'][] = $id3;
    ChangesService::saveCab($conn, $ctx1, $id3, [['analyst_id' => $a1, 'is_required' => true], ['analyst_id' => $a2, 'is_required' => true]]);
    ChangesService::updateChange($conn, $ctx1, $id3, ['status' => 'Pending Approval']);
    $r = ChangesService::voteCab($conn, $ctx1, $id3, ['vote' => 'Reject']);
    ok('a required Reject sends it to a draft-stage status', zzChange($conn, $id3)['status_stage'] === 'draft', json_encode($r));

    ChangesService::updateChange($conn, $ctx1, $id3, ['status' => 'Pending Approval']);
    $left = (int)$conn->query("SELECT COUNT(*) FROM change_cab_members WHERE change_id = {$id3} AND vote IS NOT NULL")->fetchColumn();
    ok('resubmitting clears the earlier votes', $left === 0, "$left vote(s) left");

    ChangesService::voteCab($conn, $ctx1, $id3, ['vote' => 'Approve']);
    ok('one Approve of two required does not decide it yet', zzChange($conn, $id3)['status_stage'] === 'awaiting');
    $r = ChangesService::voteCab($conn, $ctx2, $id3, ['vote' => 'Approve']);
    $row = zzChange($conn, $id3);
    ok('the second Approve approves it (before F2 the old Reject sent it back to Draft)', $row['status_stage'] === 'approved', json_encode($r));
    ok('it lands on the FIRST approved-stage status, found by stage', (int)$row['status_id'] === $zzStatus, $row['status_name'] ?? '');
    ok('and records the approval date', $row['approval_datetime'] !== null);

    // ── Settings: a required stage cannot be emptied ────────────────────────
    echo "\nSettings keep every required stage populated\n";
    $conn->beginTransaction();
    $conn->exec("UPDATE change_statuses SET is_active = 0 WHERE stage = 'awaiting'");
    $missing = changeStagesWithoutActiveStatus($conn);
    $conn->rollBack();
    ok('deactivating every awaiting status is detected', $missing === ['awaiting'], json_encode($missing));
    ok('POSITIVE CONTROL: after the rollback nothing is missing', changeStagesWithoutActiveStatus($conn) === []);

    // ── Slice B: text revisions ─────────────────────────────────────────────
    echo "\nText revisions (Part 2)\n";
    $revs = function (int $cid, string $field) use ($conn): array {
        $s = $conn->prepare("SELECT * FROM change_text_revisions WHERE change_id = ? AND field_key = ? ORDER BY id");
        $s->execute([$cid, $field]);
        return $s->fetchAll(PDO::FETCH_ASSOC);
    };
    $id4 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-REV history', 'test_plan' => '<p>plan one</p>']);
    $made['changes'][] = $id4;
    $r = $revs($id4, 'test_plan');
    ok('creating a change records its first revision', count($r) === 1 && $r[0]['content'] === '<p>plan one</p>' && !$r[0]['is_baseline']);
    ok('POSITIVE CONTROL: an empty field on create records nothing', $revs($id4, 'rollback_plan') === []);

    ChangesService::updateChange($conn, $ctx2, $id4, ['test_plan' => '<p>plan two</p>']);
    $r = $revs($id4, 'test_plan');
    ok('an edit records the whole new value, by its editor', count($r) === 2 && $r[1]['content'] === '<p>plan two</p>' && (int)$r[1]['editor_id'] === $a2);
    ok('the old value is still there in full', $r[0]['content'] === '<p>plan one</p>');
    $aud = $conn->query("SELECT field_name, new_value FROM change_audit WHERE change_id = {$id4} AND action_type = 'text_edit' ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    ok('the audit trail summarises it and points at the revision', $aud && $aud['field_name'] === 'Test plan' && $aud['new_value'] === 'Revision #' . $r[1]['id'], json_encode($aud));
    ChangesService::updateChange($conn, $ctx2, $id4, ['test_plan' => '<p>plan two</p>']);
    ok('saving identical text records nothing new', count($revs($id4, 'test_plan')) === 2);

    $api = new ActorContext($a1, null, 'api', 'en', 'ZZ api');
    ChangesService::updateChange($conn, $api, $id4, ['test_plan' => '<p>plan three</p>']);
    $r = $revs($id4, 'test_plan');
    ok('an API edit leaves the same history, marked api', end($r)['source'] === 'api');

    // A change from before the upgrade: text, but no revisions. Simulated by removing
    // the ZZ change's own rows.
    $conn->prepare("DELETE FROM change_text_revisions WHERE change_id = ?")->execute([$id4]);
    ChangesService::updateChange($conn, $ctx1, $id4, ['test_plan' => '<p>plan four</p>']);
    $r = $revs($id4, 'test_plan');
    ok('the first edit of old text writes a baseline first', count($r) === 2 && (int)$r[0]['is_baseline'] === 1);
    ok('the baseline holds the text as found, with no editor, source upgrade',
        $r[0]['content'] === '<p>plan three</p>' && $r[0]['editor_id'] === null && $r[0]['source'] === 'upgrade');
    ok('and the edit follows it', $r[1]['content'] === '<p>plan four</p>' && !$r[1]['is_baseline']);

    // The UPDATE and its revision share one transaction: roll back the transaction
    // the save ran in, and neither the text nor the revision survives.
    $before = count($revs($id4, 'test_plan'));
    $conn->beginTransaction();
    ChangesService::updateChange($conn, $ctx1, $id4, ['test_plan' => '<p>never saved</p>']);
    $conn->rollBack();
    ok('a rolled-back save leaves neither the text nor a revision',
        zzChange($conn, $id4)['test_plan'] === '<p>plan four</p>' && count($revs($id4, 'test_plan')) === $before);

    // currentId(): what an approval will point at.
    $cur = ChangeRevisions::currentId($conn, $id4, 'test_plan', '<p>plan four</p>');
    ok('currentId() is the latest revision when it matches', $cur === (int)end($r)['id']);
    $conn->prepare("UPDATE changes SET test_plan = '<p>edited behind the service</p>' WHERE id = ?")->execute([$id4]);
    $cur2 = ChangeRevisions::currentId($conn, $id4, 'test_plan', '<p>edited behind the service</p>');
    $last = $revs($id4, 'test_plan');
    ok('text changed around the service gets a system revision, not a wrong pointer',
        $cur2 !== $cur && end($last)['source'] === 'system' && end($last)['content'] === '<p>edited behind the service</p>');

    // A deleted comment stays in the evidence.
    $cmt = ChangesService::createComment($conn, $ctx2, $id4, ['text' => 'ZZ comment to delete']);
    ChangesService::deleteComment($conn, $ctx1, $cmt, $id4);
    $gone = (int)$conn->query("SELECT COUNT(*) FROM change_comments WHERE id = {$cmt}")->fetchColumn();
    $kept = $revs($id4, 'comment');
    ok('a deleted comment leaves the timeline', $gone === 0);
    ok('but its text and author are kept as a revision', count($kept) === 1 && $kept[0]['content'] === 'ZZ comment to delete' && (int)$kept[0]['editor_id'] === $a2);
    $da = $conn->query("SELECT analyst_id, new_value FROM change_audit WHERE change_id = {$id4} AND action_type = 'comment_deleted'")->fetch(PDO::FETCH_ASSOC);
    ok('and the deletion is audited, by who deleted it', $da && (int)$da['analyst_id'] === $a1 && $da['new_value'] === 'Revision #' . $kept[0]['id']);

    // ── Slice C: rounds (Record mode) ───────────────────────────────────────
    echo "\nApproval rounds, Record mode (Part 1)\n";
    $rounds = fn(int $cid) => changeApprovalRounds($conn, $cid);
    $cfg = changeApprovalSettings($conn, true);
    ok('POSITIVE CONTROL: the install is in Record mode with today\'s defaults', $cfg['mode'] === 'record' && !$cfg['edit_withdraws'] && !$cfg['stale_refused'] && !$cfg['delete_refused']);

    $id5 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-RND rounds', 'status' => 'Draft', 'cab_required' => 1,
        'test_plan' => '<p>v1</p>', 'requester_id' => $a1]);
    $made['changes'][] = $id5;
    ok('a draft has no round', $rounds($id5) === []);
    ChangesService::saveCab($conn, $ctx1, $id5, [['analyst_id' => $a1, 'is_required' => true], ['analyst_id' => $a2, 'is_required' => true]]);
    ChangesService::updateChange($conn, $ctx1, $id5, ['status' => 'Pending Approval']);
    $r = $rounds($id5);
    ok('submitting opens round 1, route cab', count($r) === 1 && $r[0]['state'] === 'open' && $r[0]['route'] === 'cab');
    ok('the snapshot points at the test plan\'s revision', !empty($r[0]['snapshot']['revisions']['test_plan']));
    ok('it is the change\'s current round', (int)zzChange($conn, $id5)['approval_id'] === $r[0]['id']);

    // The version on screen: the hash the page carries.
    $live = changeApprovalLiveState($conn, zzChange($conn, $id5), $cfg);
    ok('the live state carries the round and the version', $live['id'] === $r[0]['id'] && strlen($live['current_version']) === 64);
    ChangesService::voteCab($conn, $ctx1, $id5, ['vote' => 'Approve', 'approval_id' => $live['id'], 'content_hash' => $live['current_version']]);
    $v = $rounds($id5)[0]['votes'];
    ok('a vote sent with the current version is recorded as current', count($v) === 1 && $v[0]['was_current'] === true && $v[0]['content_hash'] === $live['current_version']);

    ChangesService::updateChange($conn, $ctx2, $id5, ['test_plan' => '<p>v2 - edited while awaiting</p>']);
    $r = $rounds($id5);
    ok('Record, keep: a material edit while awaiting keeps the round', count($r) === 1 && $r[0]['state'] === 'open');
    ok('and the live state says the content moved on', changeApprovalLiveState($conn, zzChange($conn, $id5), $cfg)['content_changed'] === true);
    ChangesService::voteCab($conn, $ctx2, $id5, ['vote' => 'Approve', 'approval_id' => $live['id'], 'content_hash' => $live['current_version']]);
    $r = $rounds($id5);
    ok('a vote from the page drawn before the edit is recorded, marked not current', $r[0]['votes'][1]['was_current'] === false);
    ok('Record still lets it count: the round is approved', $r[0]['state'] === 'approved' && zzChange($conn, $id5)['status_stage'] === 'approved');
    ok('and the approved round stays current', (int)zzChange($conn, $id5)['approval_id'] === $r[0]['id']);

    ChangesService::updateChange($conn, $ctx1, $id5, ['status' => 'Draft']);
    $r = $rounds($id5);
    ok('back to Draft withdraws the approval and clears its date', $r[0]['state'] === 'withdrawn' && zzChange($conn, $id5)['approval_datetime'] === null);

    // Stale votes refused: a setting in Record mode.
    zzSetting($conn, 'change_approval_stale_vote', 'refuse');
    ChangesService::updateChange($conn, $ctx1, $id5, ['status' => 'Pending Approval']);
    $live = changeApprovalLiveState($conn, zzChange($conn, $id5), changeApprovalSettings($conn, true));
    $code = '';
    try {
        ChangesService::voteCab($conn, $ctx1, $id5, ['vote' => 'Approve', 'approval_id' => $live['id'], 'content_hash' => str_repeat('0', 64)]);
    } catch (ServiceError $e) { $code = $e->errorCode; }
    ok('Refuse it: a vote against another version is refused (approval_stale)', $code === 'approval_stale');
    $code = '';
    try { ChangesService::voteCab($conn, $ctx1, $id5, ['vote' => 'Approve']); } catch (ServiceError $e) { $code = $e->errorCode; }
    ok('Refuse it: a vote that does not say what it saw is refused (missing_field)', $code === 'missing_field');
    ChangesService::voteCab($conn, $ctx1, $id5, ['vote' => 'Approve', 'approval_id' => $live['id'], 'content_hash' => $live['current_version']]);
    ok('POSITIVE CONTROL: the current version is accepted', count($rounds($id5)[1]['votes']) === 1);
    zzSettingRestore($conn);

    // Withdraw on edit: a setting in Record mode.
    zzSetting($conn, 'change_approval_edit_after', 'withdraw');
    changeApprovalSettings($conn, true);
    ChangesService::voteCab($conn, $ctx2, $id5, ['vote' => 'Approve']);
    ok('round 2 approved', $rounds($id5)[1]['state'] === 'approved');
    ChangesService::updateChange($conn, $ctx1, $id5, ['test_plan' => '<p>v3 - edited after approval</p>']);
    $r = $rounds($id5);
    $row = zzChange($conn, $id5);
    ok('Withdraw: a material edit after approval supersedes it', $r[1]['state'] === 'superseded' && str_contains((string)$r[1]['closed_reason'], 'Test plan'), (string)$r[1]['closed_reason']);
    ok('the change goes back to awaiting approval, its date cleared', $row['status_stage'] === 'awaiting' && $row['approval_datetime'] === null);
    ok('and round 3 opens with the new text', count($r) === 3 && $r[2]['state'] === 'open'
        && $r[2]['snapshot']['revisions']['test_plan'] !== $r[1]['snapshot']['revisions']['test_plan']);
    ChangesService::updateChange($conn, $ctx1, $id5, ['priority' => (string)$conn->query("SELECT name FROM change_priorities WHERE is_active = 1 ORDER BY display_order DESC LIMIT 1")->fetchColumn()]);
    ok('POSITIVE CONTROL: a non-material edit (priority) leaves round 3 open', count($rounds($id5)) === 3 && $rounds($id5)[2]['state'] === 'open');
    zzSettingRestore($conn);
    changeApprovalSettings($conn, true);

    // A rejection.
    ChangesService::voteCab($conn, $ctx1, $id5, ['vote' => 'Reject', 'comment' => 'ZZ not yet']);
    $r = $rounds($id5);
    ok('a required Reject closes the round as rejected, decided by the voter', $r[2]['state'] === 'rejected' && $r[2]['decided_by_id'] === $a1);
    ok('and the change goes to a draft-stage status', zzChange($conn, $id5)['status_stage'] === 'draft');
    $code = '';
    try { ChangesService::voteCab($conn, $ctx2, $id5, ['vote' => 'Approve']); } catch (ServiceError $e) { $code = $e->errorCode; }
    ok('a vote with no open round is refused (not_awaiting_approval)', $code === 'not_awaiting_approval');

    // The named approver.
    $id6 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-RND approver', 'status' => 'Draft', 'approver_id' => $a2]);
    $made['changes'][] = $id6;
    ChangesService::updateChange($conn, $ctx1, $id6, ['status' => 'Pending Approval']);
    ok('a change with a named approver opens an approver round', ($rounds($id6)[0]['route'] ?? '') === 'approver');
    $code = '';
    try { ChangesService::decideApproval($conn, $ctx1, $id6, ['decision' => 'approve']); } catch (ServiceError $e) { $code = $e->errorCode; }
    ok('somebody else cannot decide it', $code === 'forbidden');
    $res = ChangesService::decideApproval($conn, $ctx2, $id6, ['decision' => 'approve', 'note' => 'ZZ fine']);
    $r = $rounds($id6);
    ok('the approver approves it: round approved, change approved', $r[0]['state'] === 'approved' && $r[0]['decided_by_id'] === $a2
        && zzChange($conn, $id6)['status_stage'] === 'approved' && $res['status_changed']);

    // A hand-set Approved is recorded as exactly that.
    $id7 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-RND manual', 'status' => 'Draft', 'requester_id' => $a1]);
    $made['changes'][] = $id7;
    ChangesService::updateChange($conn, $ctx1, $id7, ['status' => 'Approved']);
    $r = $rounds($id7);
    ok('Record: moving to Approved by hand records a manual round', count($r) === 1 && $r[0]['route'] === 'manual' && $r[0]['state'] === 'approved');
    ok('attributed, and marked not independent (the requester did it)', $r[0]['decided_by_id'] === $a1 && $r[0]['is_independent'] === false);

    // Deleting and cancelling.
    echo "\nDeleting a submitted change (§4)\n";
    $id8 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-DEL draft', 'status' => 'Draft']);
    zzSetting($conn, 'change_approval_delete_submitted', 'refuse');
    changeApprovalSettings($conn, true);
    $code = '';
    try { ChangesService::deleteChange($conn, $ctx1, $id7); } catch (ServiceError $e) { $code = $e->errorCode; }
    ok('Not allowed: a change with approval records cannot be deleted', $code === 'change_has_approval_history');
    ChangesService::deleteChange($conn, $ctx1, $id8);
    ok('POSITIVE CONTROL: a never-submitted draft still can', !zzChange($conn, $id8));
    ChangesService::cancelChange($conn, $ctx1, $id5, ['reason' => 'ZZ no longer needed']);
    $lastStopped = (int)$conn->query("SELECT id FROM change_statuses WHERE stage = 'stopped' AND is_active = 1 ORDER BY display_order DESC, id DESC LIMIT 1")->fetchColumn();
    ok('Cancel moves it to the LAST stopped-stage status (Cancelled, not Rejected)', (int)zzChange($conn, $id5)['status_id'] === $lastStopped, zzChange($conn, $id5)['status_name'] ?? '');
    ok('and keeps every round', count($rounds($id5)) === 3);
    zzSettingRestore($conn);
    changeApprovalSettings($conn, true);
    ChangesService::deleteChange($conn, $ctx1, $id7);
    ok('Allowed (the default): it is deleted, rounds and all, with no foreign-key error', !zzChange($conn, $id7)
        && (int)$conn->query("SELECT COUNT(*) FROM change_approvals WHERE change_id = {$id7}")->fetchColumn() === 0);
    $conn->prepare("DELETE FROM changes WHERE id = ? AND title LIKE 'ZZ-%'")->execute([$id7]);
    $raw = '';
    try { $conn->prepare("DELETE FROM changes WHERE id = ?")->execute([$id5]); } catch (PDOException $e) { $raw = $e->getCode(); }
    ok('the schema backstop: a raw DELETE of a change with rounds fails', $raw !== '' && zzChange($conn, $id5));

    // ── Slice D: Enforce mode ───────────────────────────────────────────────
    echo "\nEnforce mode (Part 1 §4, §7-§10)\n";
    $code = function (callable $fn): string {
        try { $fn(); return ''; } catch (ServiceError $e) { return $e->errorCode; }
    };
    // A vote or decision sent the way the page sends it: with the version it read.
    $voteAsPage = function (ActorContext $ctx, int $cid, string $vote) use ($conn) {
        $live = changeApprovalLiveState($conn, zzChange($conn, $cid), changeApprovalSettings($conn));
        return ChangesService::voteCab($conn, $ctx, $cid, ['vote' => $vote, 'approval_id' => $live['id'], 'content_hash' => $live['current_version']]);
    };
    zzSetting($conn, 'change_approval_mode', 'enforce');
    $cfg = changeApprovalSettings($conn, true);
    ok('POSITIVE CONTROL: Enforce is on, and it fixes the three Record-only choices', $cfg['enforce'] && $cfg['edit_withdraws'] && $cfg['stale_refused'] && $cfg['delete_refused']);

    $id9 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-ENF guard', 'status' => 'Draft', 'requester_id' => $a1]);
    $made['changes'][] = $id9;
    ok('a status edit straight to Approved is refused (approval_required)', $code(fn() => ChangesService::updateChange($conn, $ctx1, $id9, ['status' => 'Approved'])) === 'approval_required');
    ok('so is In Progress, and Completed', $code(fn() => ChangesService::updateChange($conn, $ctx1, $id9, ['status' => 'In Progress'])) === 'approval_required'
        && $code(fn() => ChangesService::updateChange($conn, $ctx1, $id9, ['status' => 'Completed'])) === 'approval_required');
    ok('and the change is untouched', zzChange($conn, $id9)['status_stage'] === 'draft');
    $before = (int)$conn->query("SELECT COUNT(*) FROM changes")->fetchColumn();
    ok('creating a change straight into Completed is refused', $code(fn() => ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-ENF created done', 'status' => 'Completed'])) === 'approval_required');
    ok('and leaves no row behind (the transaction rolled back)', (int)$conn->query("SELECT COUNT(*) FROM changes")->fetchColumn() === $before);
    ok('submitting with neither a CAB nor an approver is refused', $code(fn() => ChangesService::updateChange($conn, $ctx1, $id9, ['status' => 'Pending Approval'])) === 'approval_setup_missing');
    ChangesService::updateChange($conn, $ctx1, $id9, ['approver_id' => $a1]);
    ok('so is a named approver who is the requester', $code(fn() => ChangesService::updateChange($conn, $ctx1, $id9, ['status' => 'Pending Approval'])) === 'approver_not_independent');
    ChangesService::updateChange($conn, $ctx1, $id9, ['cab_required' => 1]);
    ChangesService::saveCab($conn, $ctx1, $id9, [['analyst_id' => $a1, 'is_required' => true]]);
    ok('and a CAB whose only required member is the requester', $code(fn() => ChangesService::updateChange($conn, $ctx1, $id9, ['status' => 'Pending Approval'])) === 'cab_unreachable');

    ChangesService::saveCab($conn, $ctx1, $id9, [['analyst_id' => $a1, 'is_required' => true], ['analyst_id' => $a2, 'is_required' => true]]);
    ChangesService::updateChange($conn, $ctx1, $id9, ['status' => 'Pending Approval']);
    ok('POSITIVE CONTROL: with an independent required member it is submitted', zzChange($conn, $id9)['status_stage'] === 'awaiting');
    ok('the roster is now fixed (approval_locked)', $code(fn() => ChangesService::saveCab($conn, $ctx1, $id9, [['analyst_id' => $a2, 'is_required' => true]])) === 'approval_locked');
    ok('so is the approver field', $code(fn() => ChangesService::updateChange($conn, $ctx1, $id9, ['approver_id' => $a2])) === 'approval_locked');
    ok('a vote that does not say what it read is refused', $code(fn() => ChangesService::voteCab($conn, $ctx1, $id9, ['vote' => 'Approve'])) === 'missing_field');
    $r = $voteAsPage($ctx1, $id9, 'Approve');
    ok('the requester\'s own vote is recorded but not counted', $r['counted'] === false && zzChange($conn, $id9)['status_stage'] === 'awaiting');
    $r = $voteAsPage($ctx2, $id9, 'Approve');
    $rd = $rounds($id9);
    ok('the independent member\'s vote decides it', zzChange($conn, $id9)['status_stage'] === 'approved' && end($rd)['state'] === 'approved');
    ok('and the round is independent (the uncounted vote did not make it so)', end($rd)['is_independent'] === true);
    ChangesService::updateChange($conn, $ctx2, $id9, ['status' => 'In Progress']);
    ok('POSITIVE CONTROL: an approved change may move on to In Progress', zzChange($conn, $id9)['status_stage'] === 'implementing');
    ChangesService::updateChange($conn, $ctx2, $id9, ['rollback_plan' => '<p>ZZ new rollback</p>']);
    $rd = $rounds($id9);
    ok('a material edit withdraws the approval and sends it back for approval', zzChange($conn, $id9)['status_stage'] === 'awaiting'
        && end($rd)['state'] === 'open');
    ok('a submitted change cannot be deleted in Enforce mode', $code(fn() => ChangesService::deleteChange($conn, $ctx1, $id9)) === 'change_has_approval_history');
    ChangesService::updateChange($conn, $ctx1, $id9, ['status' => 'Draft']);
    ChangesService::updateChange($conn, $ctx1, $id9, ['approver_id' => $a2]);
    ok('back in Draft the approvers can be changed again', (int)zzChange($conn, $id9)['approver_id'] === $a2);

    // Emergency - approve afterwards.
    $conn->prepare("INSERT INTO change_types (name, colour, approval_route, is_default, display_order, is_active) VALUES ('ZZ Emergency', '#000000', 'emergency', 0, 999, 1)")->execute();
    $made['types'][] = $zzType = (int)$conn->lastInsertId();
    $id10 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-ENF emergency', 'status' => 'Draft', 'change_type_id' => $zzType, 'approver_id' => $a2, 'requester_id' => $a1]);
    $made['changes'][] = $id10;
    ChangesService::updateChange($conn, $ctx1, $id10, ['status' => 'Pending Approval']);
    ok('an emergency change opens an emergency round', ($rounds($id10)[0]['route'] ?? '') === 'emergency');
    ChangesService::updateChange($conn, $ctx1, $id10, ['status' => 'In Progress']);
    ok('it may go ahead before its decision', zzChange($conn, $id10)['status_stage'] === 'implementing' && $rounds($id10)[0]['state'] === 'open');
    ok('Watchtower counts it as awaiting retrospective approval', changeApprovalEmergencyOpenCount($conn, ' AND c.id = ?', [$id10]) === 1);
    ok('but not into Approved, which only a decision does', $code(fn() => ChangesService::updateChange($conn, $ctx1, $id10, ['status' => 'Approved'])) === 'approval_required');
    $live = changeApprovalLiveState($conn, zzChange($conn, $id10), changeApprovalSettings($conn));
    ChangesService::decideApproval($conn, $ctx2, $id10, ['decision' => 'approve', 'approval_id' => $live['id'], 'content_hash' => $live['current_version']]);
    ok('the approver approves it afterwards, without moving it back', $rounds($id10)[0]['state'] === 'approved' && zzChange($conn, $id10)['status_stage'] === 'implementing'
        && zzChange($conn, $id10)['approval_datetime'] !== null);
    ok('and it leaves the Watchtower count', changeApprovalEmergencyOpenCount($conn, ' AND c.id = ?', [$id10]) === 0);

    // The administrator override.
    $id11 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-ENF override', 'status' => 'Draft', 'approver_id' => $a2]);
    $made['changes'][] = $id11;
    ChangesService::updateChange($conn, $ctx1, $id11, ['status' => 'Pending Approval']);
    ok('the override is off by default', $code(fn() => ChangesService::overrideApproval($conn, $ctx1, $id11, ['reason' => 'ZZ'])) === 'override_off');
    zzSetting($conn, 'change_approval_override', '1');
    changeApprovalSettings($conn, true);
    ok('switched on, it needs a reason', $code(fn() => ChangesService::overrideApproval($conn, $ctx1, $id11, ['reason' => ''])) === 'missing_field');
    $nonAdmin = (int)$conn->query("SELECT id FROM analysts WHERE is_active = 1 AND is_admin = 0 ORDER BY id LIMIT 1")->fetchColumn();
    if ($nonAdmin) {
        ok('and an administrator', $code(fn() => ChangesService::overrideApproval($conn, new ActorContext($nonAdmin, null, 'ui', 'en', 'ZZ'), $id11, ['reason' => 'ZZ'])) === 'forbidden');
    }
    ChangesService::overrideApproval($conn, $ctx1, $id11, ['reason' => 'ZZ board unreachable']);
    $rd = $rounds($id11);
    ok('an administrator approves it by override: recorded as such, with the reason', end($rd)['route'] === 'override' && end($rd)['decision_note'] === 'ZZ board unreachable'
        && zzChange($conn, $id11)['status_stage'] === 'approved');

    // ── Slice G: standard changes from approved templates ──────────────────
    echo "\nStandard changes from approved templates (§8)\n";
    zzSettingRestore($conn);
    changeApprovalSettings($conn, true);
    require_once __DIR__ . '/../includes/changes/templates.php';
    $conn->prepare("INSERT INTO change_templates (name, scope_use, scope_not, open_fields, test_plan_template, rollback_plan_template, created_by_id, created_datetime)
                    VALUES ('ZZ Restart print spooler', 'One print server', 'Domain controllers', 'description', 'Print a test page.', 'Restart the service again.', ?, UTC_TIMESTAMP())")->execute([$a1]);
    $made['templates'][] = $tplId = (int)$conn->lastInsertId();
    $v1 = ChangeTemplates::submitVersion($conn, $tplId, $a1);
    ok('the author cannot approve their own version (an administrator included)',
        $code(fn() => ChangeTemplates::approveVersion($conn, $v1, $a1, 'ZZ')) === 'not_independent');
    $v1 = ChangeTemplates::submitVersion($conn, $tplId, $a2);   // resubmitted by somebody else
    ChangeTemplates::approveVersion($conn, $v1, $a1, 'ZZ approved at CAB');
    ok('another approver approves it', ChangeTemplates::version($conn, $v1)['state'] === 'approved');

    $raise = fn(array $extra = []) => ChangesService::createChange($conn, $ctx2, 1, array_merge(
        ['title' => 'ZZ-TPL change', 'status' => 'Draft', 'template_version_id' => $v1, 'description' => '<p>PRN01</p>'], $extra));
    ok('the template fills in what the change leaves out', ($tid = $raise()) && zzChange($conn, $tid)['test_plan'] === 'Print a test page.');
    $made['changes'][] = $tid;

    ChangesService::updateChange($conn, $ctx2, $tid, ['status' => 'Pending Approval']);
    $rd = $rounds($tid);
    ok('switched off (the default): it takes the normal route', ($rd[0]['route'] ?? '') !== 'pre_authorised');

    zzSetting($conn, 'change_templates_enabled', '1');
    changeApprovalSettings($conn, true);
    $t2 = $raise(['test_plan' => '<p>Print a test page.</p>']);   // the editor's markup, the same words
    $made['changes'][] = $t2;
    ChangesService::updateChange($conn, $ctx2, $t2, ['status' => 'Pending Approval']);
    $rd = $rounds($t2);
    ok('switched on, untouched (markup aside): pre-authorised on submission', ($rd[0]['route'] ?? '') === 'pre_authorised' && $rd[0]['state'] === 'approved');
    ok('and moved on to the approved stage, its approval dated', zzChange($conn, $t2)['status_stage'] === 'approved' && zzChange($conn, $t2)['approval_datetime'] !== null);
    ok('the round names the template version and its approver', str_contains((string)$rd[0]['decision_note'], 'ZZ Restart print spooler" v')
        && $rd[0]['snapshot']['template_version_id'] === $v1);

    $t3 = $raise(['rollback_plan' => '<p>Reboot the whole server.</p>']);
    $made['changes'][] = $t3;
    ChangesService::updateChange($conn, $ctx2, $t3, ['status' => 'Pending Approval']);
    ok('the rollback plan altered: normal route', ($rounds($t3)[0]['route'] ?? '') !== 'pre_authorised');
    $chk = ChangeTemplates::check($conn, zzChange($conn, $t3), changeApprovalSettings($conn), $a2);
    ok('and the reason names the field', $chk['reason'] === 'changed' && $chk['changed_fields'] === ['rollback_plan'], json_encode($chk['changed_fields'] ?? null));
    $t4 = $raise(['description' => '<p>PRN02 - a different server</p>']);
    $made['changes'][] = $t4;
    ChangesService::updateChange($conn, $ctx2, $t4, ['status' => 'Pending Approval']);
    ok('POSITIVE CONTROL: the description is completed per change, so a different one keeps it pre-authorised',
        ($rounds($t4)[0]['route'] ?? '') === 'pre_authorised');

    zzSetting($conn, 'change_approval_mode', 'enforce');
    changeApprovalSettings($conn, true);
    $t5 = $raise();
    $made['changes'][] = $t5;
    ChangesService::updateChange($conn, $ctx2, $t5, ['status' => 'In Progress']);
    ok('Enforce: an untouched standard change may go straight to In Progress', zzChange($conn, $t5)['status_stage'] === 'implementing'
        && ($rounds($t5)[0]['route'] ?? '') === 'pre_authorised');
    $t6 = $raise(['test_plan' => '<p>Skip testing.</p>']);
    $made['changes'][] = $t6;
    ok('Enforce: an altered one may not', $code(fn() => ChangesService::updateChange($conn, $ctx2, $t6, ['status' => 'In Progress'])) === 'approval_required');

    // Who may use it.
    $otherTeam = (int)$conn->query("SELECT id FROM teams WHERE id NOT IN (SELECT team_id FROM analyst_teams WHERE analyst_id = {$a2}) ORDER BY id LIMIT 1")->fetchColumn();
    if (!$otherTeam) throw new RuntimeException("needs a team analyst {$a2} is not in");
    $conn->prepare("INSERT INTO change_template_teams (template_id, team_id) VALUES (?, ?)")->execute([$tplId, $otherTeam]);
    $v2 = ChangeTemplates::submitVersion($conn, $tplId, $a2);
    ChangeTemplates::approveVersion($conn, $v2, $a1, 'ZZ team-only');
    ok('a version superseded by a newer approval stops pre-authorising', ChangeTemplates::version($conn, $v1)['state'] === 'superseded');
    ok('restricted to a team they are not in, they cannot raise from it',
        $code(fn() => ChangesService::createChange($conn, $ctx2, 1, ['title' => 'ZZ-TPL refused', 'template_version_id' => $v2])) === 'invalid_field');
    ChangeTemplates::retire($conn, $tplId);
    ok('retired: no longer offered to anybody', !array_filter(ChangeTemplates::usable($conn, $a1), fn($t) => $t['template_id'] === $tplId));

    zzSettingRestore($conn);
    changeApprovalSettings($conn, true);
    $id12 = ChangesService::createChange($conn, $ctx1, 1, ['title' => 'ZZ-ENF negative control', 'status' => 'Draft']);
    $made['changes'][] = $id12;
    ChangesService::updateChange($conn, $ctx1, $id12, ['status' => 'Approved']);
    ok('NEGATIVE CONTROL: back in Record mode the same hand-set Approved goes through', zzChange($conn, $id12)['status_stage'] === 'approved');
} catch (Throwable $e) {
    ok('no unexpected exception', false, get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine());
} finally {
    zzSettingRestore($conn);
    foreach ($made['changes'] as $cid) {
        // change_approvals is ON DELETE RESTRICT (a change with rounds is evidence), so
        // a ZZ change's rounds go first; their votes cascade.
        $ok = $conn->prepare("SELECT 1 FROM changes WHERE id = ? AND title LIKE 'ZZ-%'");
        $ok->execute([$cid]);
        if (!$ok->fetchColumn()) continue;
        $conn->prepare("UPDATE changes SET approval_id = NULL WHERE id = ?")->execute([$cid]);
        $conn->prepare("DELETE FROM change_approvals WHERE change_id = ?")->execute([$cid]);
        $conn->prepare("DELETE FROM changes WHERE id = ? AND title LIKE 'ZZ-%'")->execute([$cid]);
    }
    foreach ($made['templates'] as $tplDel) {
        $conn->prepare("DELETE FROM change_template_teams WHERE template_id = ?")->execute([$tplDel]);
        $conn->prepare("DELETE FROM change_template_versions WHERE template_id = ?")->execute([$tplDel]);
        $conn->prepare("DELETE FROM change_templates WHERE id = ? AND name LIKE 'ZZ %'")->execute([$tplDel]);
    }
    foreach ($made['types'] as $tid) {
        $conn->prepare("DELETE FROM change_types WHERE id = ? AND name LIKE 'ZZ %'")->execute([$tid]);
    }
    foreach ($made['statuses'] as $sid) {
        $conn->prepare("DELETE FROM change_statuses WHERE id = ? AND name LIKE 'ZZ %'")->execute([$sid]);
    }
    $leftC = (int)$conn->query("SELECT COUNT(*) FROM changes WHERE title LIKE 'ZZ-%'")->fetchColumn();
    $leftS = (int)$conn->query("SELECT COUNT(*) FROM change_statuses WHERE name LIKE 'ZZ %'")->fetchColumn();
    echo "\nTidy: " . ($leftC + $leftS === 0 ? 'nothing left behind' : "LEFT BEHIND: {$leftC} change(s), {$leftS} status(es)") . "\n";
}

echo "\n" . str_repeat('-', 70) . "\n  {$pass} passed, {$fail} failed\n";
exit($fail > 0 ? 1 : 0);
