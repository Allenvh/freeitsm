<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Drafts — a form somebody started and did not finish.
 *
 * 🔑 WHAT HAS TO HOLD, and why none of it fails loudly on its own:
 *
 *   1. A draft is NEVER validated. Not being finished is the point: a required
 *      field may be empty and a number box may hold "tbc". If validation ever
 *      creeps in, the feature stops doing the one thing it exists for.
 *
 *   2. A draft cannot be used to park arbitrary data. Keys must be field ids
 *      belonging to that form; anything else is dropped rather than stored.
 *
 *   3. 🔴 A draft is pinned to the form VERSION it was typed into, and a draft
 *      whose version has been superseded is reported as STALE. createVersion()
 *      renumbers every field, so loading old answers into a newer version would
 *      attach them to whatever now holds those ids — silently, and wrongly.
 *
 *   4. Drafts stay OUT of form_submissions, so nothing that reads submissions
 *      had to be taught anything.
 *
 * ⚠️ Runs against the real database. Everything it creates is deleted in the
 * finally block and the cleanup is asserted, not assumed.
 *
 * Run: php tests/form-drafts.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/forms.php';

$pass = 0; $fail = 0;
function check(string $label, bool $ok, string $detail = ''): void {
    global $pass, $fail;
    if ($ok) { $pass++; echo "  ok    $label\n"; }
    else     { $fail++; echo "  FAIL  $label" . ($detail !== '' ? "  — $detail" : '') . "\n"; }
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Form drafts\n" . str_repeat('=', 64) . "\n";

if (!FormsService::draftsAvailable($conn)) {
    echo "  SKIPPED — form_drafts does not exist yet. Run Database Verification.\n";
    echo "  ⚠️ A skip is not a pass. See feedback on chasing a SKIP.\n";
    exit(0);
}

$madeForms = [];
try {
    /* A form with a required question and an optional one. */
    $conn->prepare("INSERT INTO forms (title, is_active, created_date, modified_date)
                    VALUES ('ZZ draft probe', 1, UTC_TIMESTAMP(), UTC_TIMESTAMP())")->execute();
    $formId = (int)$conn->lastInsertId();
    $madeForms[] = $formId;

    $mkField = function (int $fid, string $label, int $required, int $sort) use ($conn): int {
        $conn->prepare("INSERT INTO form_fields (form_id, field_type, label, is_required, sort_order)
                        VALUES (?, 'text', ?, ?, ?)")->execute([$fid, $label, $required, $sort]);
        return (int)$conn->lastInsertId();
    };
    $req = $mkField($formId, 'Cost code', 1, 0);
    $opt = $mkField($formId, 'Notes',     0, 1);

    /* ── 1. A half-filled draft saves, required field empty ──────────────── */
    $r = FormsService::saveDraft($conn, $formId, 'analyst', 1, [
        $req => '',                 // required, and deliberately blank
        $opt => 'waiting on finance',
    ]);
    check('a draft saves with a REQUIRED field left empty', !empty($r['saved']));

    $d = FormsService::loadDraft($conn, $formId, 'analyst', 1);
    check('it reads back', $d !== null);
    check('the empty required answer survived', ($d['answers'][$req] ?? null) === '');
    check('the typed answer survived', ($d['answers'][$opt] ?? null) === 'waiting on finance');
    check('a fresh draft is NOT stale', $d['stale'] === false);

    /* ── 2. Saving again overwrites rather than accumulating ─────────────── */
    FormsService::saveDraft($conn, $formId, 'analyst', 1, [$opt => 'finance replied']);
    $n = $conn->prepare("SELECT COUNT(*) FROM form_drafts WHERE form_id = ? AND owner_kind = 'analyst' AND owner_id = 1");
    $n->execute([$formId]);
    check('saving again overwrites, it does not pile up', (int)$n->fetchColumn() === 1);
    $d = FormsService::loadDraft($conn, $formId, 'analyst', 1);
    check('the newer text won', ($d['answers'][$opt] ?? null) === 'finance replied');

    /* ── 3. Two different people, two different drafts ───────────────────── */
    FormsService::saveDraft($conn, $formId, 'analyst', 2, [$opt => 'somebody else']);
    $mine   = FormsService::loadDraft($conn, $formId, 'analyst', 1);
    $theirs = FormsService::loadDraft($conn, $formId, 'analyst', 2);
    check('one person\'s draft is not another\'s',
        $mine['answers'][$opt] === 'finance replied' && $theirs['answers'][$opt] === 'somebody else');

    /* An ANALYST 1 and a PORTAL user 1 are different people with the same
       number — the id-space collision form_submissions documents. */
    FormsService::saveDraft($conn, $formId, 'portal', 1, [$opt => 'a customer']);
    $a = FormsService::loadDraft($conn, $formId, 'analyst', 1);
    $p = FormsService::loadDraft($conn, $formId, 'portal',  1);
    check('analyst 1 and portal user 1 are NOT the same owner',
        $a['answers'][$opt] === 'finance replied' && $p['answers'][$opt] === 'a customer',
        'analysts and users are separate id spaces');

    /* ── 4. A draft cannot park arbitrary data ───────────────────────────── */
    FormsService::saveDraft($conn, $formId, 'analyst', 3, [
        $opt      => 'legitimate',
        999999    => 'a field id from another form',
        'notanid' => 'nonsense',
    ]);
    $d = FormsService::loadDraft($conn, $formId, 'analyst', 3);
    check('a key that is not this form\'s field is dropped',
        count($d['answers']) === 1 && ($d['answers'][$opt] ?? null) === 'legitimate',
        'kept: ' . implode(',', array_keys($d['answers'])));

    /* ── 5. 🔴 THE VERSION TRAP ──────────────────────────────────────────── */
    $conn->prepare("INSERT INTO forms (title, is_active, parent_form_id, version_number, created_date, modified_date)
                    VALUES ('ZZ draft probe', 1, ?, 2, UTC_TIMESTAMP(), UTC_TIMESTAMP())")->execute([$formId]);
    $v2 = (int)$conn->lastInsertId();
    $madeForms[] = $v2;

    $d = FormsService::loadDraft($conn, $formId, 'analyst', 1);
    check('a draft whose form has a NEWER VERSION reports itself stale',
        $d['stale'] === true,
        'without this the answers would load into a version whose fields have different ids');
    check('the stale draft still returns its answers, for the caller to decide',
        ($d['answers'][$opt] ?? null) === 'finance replied',
        'discarding here would throw away somebody\'s typing without asking');

    /* ── 6. Nothing reached form_submissions ─────────────────────────────── */
    $s = $conn->prepare("SELECT COUNT(*) FROM form_submissions WHERE form_id IN (?, ?)");
    $s->execute([$formId, $v2]);
    check('NOTHING was written to form_submissions', (int)$s->fetchColumn() === 0,
        'a draft appearing there is a half-filled record shown as a real one');

    /* ── 7. Deleting ─────────────────────────────────────────────────────── */
    FormsService::deleteDraft($conn, $formId, 'analyst', 1);
    check('a deleted draft is gone', FormsService::loadDraft($conn, $formId, 'analyst', 1) === null);
    FormsService::deleteDraft($conn, $formId, 'analyst', 1);
    check('deleting one that is already gone is not an error', true);

    /* ── 8. The list a "you have a draft" marker would use ───────────────── */
    $list = FormsService::draftsForOwner($conn, 'analyst', 2);
    check('draftsForOwner finds this person\'s draft', isset($list[$formId]));
    check('and does not find somebody else\'s',
        !isset(FormsService::draftsForOwner($conn, 'analyst', 99)[$formId]));

    /* ── 9. Refusals ─────────────────────────────────────────────────────── */
    $threw = false;
    try { FormsService::saveDraft($conn, $formId, 'wizard', 1, [$opt => 'x']); }
    catch (ServiceError $e) { $threw = true; }
    check('an unknown owner kind is refused', $threw);

    $threw = false;
    try { FormsService::saveDraft($conn, 0, 'analyst', 1, [$opt => 'x']); }
    catch (ServiceError $e) { $threw = true; }
    check('a missing form id is refused', $threw);

    /* CONTROL: the legitimate call still works, or every refusal above passes
       simply because saveDraft() refuses everything. */
    $ok = FormsService::saveDraft($conn, $formId, 'analyst', 4, [$opt => 'control']);
    check('CONTROL — a valid save still succeeds', !empty($ok['saved']));

} finally {
    foreach ($madeForms as $fid) {
        $conn->prepare("DELETE FROM form_drafts WHERE form_id = ?")->execute([$fid]);
        $conn->prepare("DELETE FROM form_fields WHERE form_id = ?")->execute([$fid]);
    }
    /* Children first: parent_form_id is RESTRICT, so the v2 row has to go
       before the v1 it chains off. */
    foreach (array_reverse($madeForms) as $fid) {
        $conn->prepare("DELETE FROM forms WHERE id = ?")->execute([$fid]);
    }
    $left = 0;
    if ($madeForms) {
        $in = implode(',', array_fill(0, count($madeForms), '?'));
        $q = $conn->prepare("SELECT COUNT(*) FROM forms WHERE id IN ($in)");
        $q->execute($madeForms);
        $left = (int)$q->fetchColumn();
    }
    check('CLEANED UP — no probe forms or drafts remain', $left === 0, "forms left={$left}");
}

echo "\n" . str_repeat('=', 64) . "\n";
echo "$pass passed, $fail failed\n";
exit($fail > 0 ? 1 : 0);
