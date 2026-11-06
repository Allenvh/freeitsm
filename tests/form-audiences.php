<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Restricting a form to a group of people (GH #145, Benjamin).
 *
 * 🔴 WHAT HAS TO HOLD. A restriction is only worth the name if EVERY way in
 * enforces it. The catalogue hiding a card is not a check — somebody who was in
 * the group last week, or who has a colleague's link, arrives at the other
 * endpoints with a perfectly valid form id.
 *
 *   the catalogue list        — the form is not in it
 *   opening it by id          — 404, same as a form that does not exist
 *   saving a draft of it      — refused
 *   fetching its images       — 404
 *   SUBMITTING it             — refused in the service, not just the adapter
 *
 * 🔑 NO ROWS MEANS EVERYONE, and that is every form that predates this. The
 * first thing proved below is that an unrestricted form is unaffected, because
 * a restriction feature that quietly narrows existing forms is the worst
 * possible outcome.
 *
 * ⚠️ Runs against the real database. Everything is removed in the finally
 * block and the cleanup is asserted.
 *
 * Run: php tests/form-audiences.php
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

echo "Restricting a form to a group of people\n" . str_repeat('=', 64) . "\n";

if (!FormsService::audiencesAvailable($conn)) {
    echo "  SKIPPED — form_audiences does not exist. Run Database Verification.\n";
    echo "  ⚠️ A skip is not a pass.\n";
    exit(0);
}

$madeForms = []; $madeGroups = [];
try {
    $conn->prepare("INSERT INTO forms (title, is_active, is_portal_visible, created_date, modified_date)
                    VALUES ('ZZ audience probe', 1, 1, UTC_TIMESTAMP(), UTC_TIMESTAMP())")->execute();
    $formId = (int)$conn->lastInsertId(); $madeForms[] = $formId;
    $conn->prepare("INSERT INTO form_fields (form_id, field_type, label, is_required, sort_order)
                    VALUES (?, 'text', 'Why', 0, 0)")->execute([$formId]);

    /* Two real portal users: one we will put in the group, one we will not. */
    $users = $conn->query("SELECT id FROM users ORDER BY id LIMIT 2")->fetchAll(PDO::FETCH_COLUMN);
    if (count($users) < 2) {
        echo "  SKIPPED — need two portal users to tell 'allowed' from 'refused' apart.\n";
        echo "  ⚠️ A skip is not a pass.\n";
        exit(0);
    }
    [$inGroup, $outGroup] = array_map('intval', $users);

    $conn->prepare("INSERT INTO knowledge_user_groups (name, description, is_active)
                    VALUES ('ZZ audience probe group', 'test', 1)")->execute();
    $groupId = (int)$conn->lastInsertId(); $madeGroups[] = $groupId;
    $conn->prepare("INSERT INTO knowledge_user_group_members (group_id, member_type, member_id)
                    VALUES (?, 'user', ?)")->execute([$groupId, $inGroup]);

    /* ── 1. No audience means EVERYONE ───────────────────────────────────── */
    check('with NO audience, the in-group person may use it',
        FormsService::portalCanUseForm($conn, $formId, $inGroup));
    check('with NO audience, the out-of-group person may use it TOO',
        FormsService::portalCanUseForm($conn, $formId, $outGroup),
        'no rows must mean everyone, or every existing form silently narrows');

    /* ── 2. Restricting it ───────────────────────────────────────────────── */
    FormsService::setFormAudiences($conn, $formId, [$groupId]);
    check('the audience saved', FormsService::formAudiences($conn, $formId) === [$groupId]);

    check('the person IN the group may still use it',
        FormsService::portalCanUseForm($conn, $formId, $inGroup));
    check('the person OUTSIDE the group may NOT',
        !FormsService::portalCanUseForm($conn, $formId, $outGroup),
        'this is the whole feature');

    /* ── 3. Submitting is refused in the SERVICE ─────────────────────────── */
    /* A portal submission still carries an ActorContext; the PORTAL user is the
       separate $portalUserId argument, which is what submitForm() gates on.
       actorId 0 is "no analyst", which is exactly what a catalogue request is. */
    $ctx = new ActorContext(actorId: 0, source: 'ui');
    $threw = false;
    try {
        FormsService::submitForm($conn, $ctx, $formId, [], $outGroup);
    } catch (ServiceError $e) {
        $threw = true;
        check('the refusal says NOT FOUND, not FORBIDDEN', $e->kind === 'not_found',
            'got ' . $e->kind . ' — "not for you" tells a customer the form exists');
    }
    check('submitting a restricted form is refused in the service', $threw,
        'the catalogue hiding a card has never been a check');

    /* CONTROL: the in-group person is NOT refused for the same reason — if they
       were, the refusal above would prove nothing about the audience. */
    $refusedForAudience = false;
    try {
        FormsService::submitForm($conn, $ctx, $formId, [], $inGroup);
    } catch (ServiceError $e) {
        // A validation complaint about the answers is fine and expected; being
        // told the form does not exist is not.
        $refusedForAudience = ($e->kind === 'not_found');
    }
    check('CONTROL — the in-group person is NOT refused as "not found"',
        !$refusedForAudience,
        'if they were, the refusal above is not evidence of anything');

    /* ── 4. A lapsed membership is not a membership ──────────────────────── */
    $conn->prepare("UPDATE knowledge_user_group_members SET expires_at = DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 DAY)
                     WHERE group_id = ? AND member_id = ?")->execute([$groupId, $inGroup]);
    check('an EXPIRED group membership no longer grants access',
        !FormsService::portalCanUseForm($conn, $formId, $inGroup),
        'people groups carry a per-member expiry and it has to be honoured');
    $conn->prepare("UPDATE knowledge_user_group_members SET expires_at = NULL
                     WHERE group_id = ? AND member_id = ?")->execute([$groupId, $inGroup]);

    /* ── 5. A deactivated group fails CLOSED ─────────────────────────────── */
    $conn->prepare("UPDATE knowledge_user_groups SET is_active = 0 WHERE id = ?")->execute([$groupId]);
    check('a DEACTIVATED group narrows the form rather than opening it up',
        !FormsService::portalCanUseForm($conn, $formId, $inGroup),
        'failing open here would republish a restricted form to everyone');
    $conn->prepare("UPDATE knowledge_user_groups SET is_active = 1 WHERE id = ?")->execute([$groupId]);

    /* ── 6. Unknown groups are refused rather than stored ────────────────── */
    $threw = false;
    try { FormsService::setFormAudiences($conn, $formId, [999999]); }
    catch (ServiceError $e) { $threw = true; }
    check('a group id that does not exist is refused', $threw,
        'storing it would restrict the form to NOBODY and look identical to working');

    /* ── 7. Clearing it goes back to everyone ────────────────────────────── */
    FormsService::setFormAudiences($conn, $formId, []);
    check('clearing the audience returns the form to everyone',
        FormsService::portalCanUseForm($conn, $formId, $outGroup));

    /* ── 8. 🔴 A NEW VERSION KEEPS THE RESTRICTION ───────────────────────── */
    FormsService::setFormAudiences($conn, $formId, [$groupId]);
    $newId = FormsService::createVersion($conn, new ActorContext(actorId: 1, source: 'ui'), $formId)['id'] ?? 0;
    if ($newId) {
        $madeForms[] = $newId;
        check('a new version inherits the audience',
            FormsService::formAudiences($conn, (int)$newId) === [$groupId],
            'losing it republishes a restricted form to every customer, silently');
        check('and the new version refuses the out-of-group person',
            !FormsService::portalCanUseForm($conn, (int)$newId, $outGroup));
    } else {
        check('createVersion returned a new form id', false, 'could not test the carry-forward');
    }

} finally {
    foreach (array_reverse($madeForms) as $fid) {
        $conn->prepare("DELETE FROM form_audiences WHERE form_id = ?")->execute([$fid]);
        $conn->prepare("DELETE FROM form_submissions WHERE form_id = ?")->execute([$fid]);
        $conn->prepare("DELETE FROM form_fields WHERE form_id = ?")->execute([$fid]);
        $conn->prepare("DELETE FROM forms WHERE id = ?")->execute([$fid]);
    }
    foreach ($madeGroups as $gid) {
        $conn->prepare("DELETE FROM knowledge_user_group_members WHERE group_id = ?")->execute([$gid]);
        $conn->prepare("DELETE FROM knowledge_user_groups WHERE id = ?")->execute([$gid]);
    }
    $left = 0;
    if ($madeForms) {
        $in = implode(',', array_fill(0, count($madeForms), '?'));
        $q = $conn->prepare("SELECT COUNT(*) FROM forms WHERE id IN ($in)"); $q->execute($madeForms);
        $left = (int)$q->fetchColumn();
    }
    check('CLEANED UP — no probe forms or groups remain', $left === 0, "forms left={$left}");
}

echo "\n" . str_repeat('=', 64) . "\n";
echo "$pass passed, $fail failed\n";
exit($fail > 0 ? 1 : 0);
