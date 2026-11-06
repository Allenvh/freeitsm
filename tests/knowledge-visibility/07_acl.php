<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Folders, the access list, and the two permission models.
 *
 * Everything runs inside ONE transaction that is ALWAYS rolled back, so this is
 * safe against a live database.
 *
 * ⚠️ EVERY REFUSAL IS PAIRED WITH A POSITIVE CONTROL. "Bob cannot see it" is
 * equally true of a filter that hides everything from everyone, which is exactly
 * how a broken permission system looks from the denied side. So each case also
 * asserts that somebody who SHOULD see the same article still does.
 *
 *   php tests/knowledge-visibility/07_acl.php
 */

$APP = dirname(__DIR__, 2);
require_once __DIR__ . '/_bootstrap.php';
require_once $APP . '/config.php';
require_once $APP . '/includes/functions.php';
require_once $APP . '/includes/knowledge/visibility.php';

$pass = 0; $fail = 0;
function check(string $what, bool $ok, string $detail = ''): void {
    global $pass, $fail;
    if ($ok) { $pass++; echo "  PASS  $what\n"; }
    else     { $fail++; echo "  FAIL  $what" . ($detail !== '' ? "\n        -> $detail" : '') . "\n"; }
}

$c = connectToDatabase();
$c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach (['knowledge_folders', 'knowledge_acl', 'knowledge_audit', 'knowledge_user_groups'] as $t) {
    if (!$c->query("SHOW TABLES LIKE '$t'")->fetchColumn()) {
        fwrite(STDERR, "$t is missing. Run System -> Database Verification first.\n");
        exit(1);
    }
}

$c->beginTransaction();
try {
    // ── People ──────────────────────────────────────────────────────────────
    $mkAnalyst = function (string $u) use ($c) {
        $c->prepare("INSERT INTO analysts (username, password_hash, full_name, email, is_active, is_admin, can_access_all_tenants, can_access_all_modules, created_datetime)
                     VALUES (?, ?, ?, ?, 1, 0, 1, 1, UTC_TIMESTAMP())")
          ->execute([$u, password_hash('x', PASSWORD_DEFAULT), strtoupper($u), $u . '@zz.test']);
        return (int)$c->lastInsertId();
    };
    $alice = $mkAnalyst('zz-alice');   // will be denied / not granted
    $bob   = $mkAnalyst('zz-bob');     // the positive control throughout
    $carol = $mkAnalyst('zz-carol');   // reached only through a team
    $dave  = $mkAnalyst('zz-dave');    // reached only through a user group

    $c->prepare("INSERT INTO teams (name, can_access_all_tenants, can_access_all_modules, created_datetime) VALUES ('ZZ Team', 1, 1, UTC_TIMESTAMP())")->execute();
    $team = (int)$c->lastInsertId();
    $c->prepare("INSERT INTO analyst_teams (analyst_id, team_id) VALUES (?, ?)")->execute([$carol, $team]);

    $c->prepare("INSERT INTO knowledge_user_groups (name, is_active, created_datetime) VALUES ('ZZ Group', 1, UTC_TIMESTAMP())")->execute();
    $group = (int)$c->lastInsertId();

    // ── Folders and articles ────────────────────────────────────────────────
    $mkFolder = function (?int $parent, int $restricted, int $inherit) use ($c) {
        $c->prepare("INSERT INTO knowledge_folders (parent_id, name, is_restricted, inherit_permissions, created_datetime)
                     VALUES (?, 'ZZ folder', ?, ?, UTC_TIMESTAMP())")->execute([$parent, $restricted, $inherit]);
        return (int)$c->lastInsertId();
    };
    $mkArticle = function (?int $folder, int $restricted = 0, int $inherit = 1) use ($c, $bob) {
        $c->prepare("INSERT INTO knowledge_articles (title, body, author_id, is_published, is_archived, created_datetime, modified_datetime, tenant_id, audience, folder_id, is_restricted, inherit_permissions)
                     VALUES ('ZZ-ACL', '<p>x</p>', ?, 1, 0, UTC_TIMESTAMP(), UTC_TIMESTAMP(), NULL, 'internal', ?, ?, ?)")
          ->execute([$bob, $folder, $restricted, $inherit]);
        return (int)$c->lastInsertId();
    };
    $ace = function (string $otype, int $oid, string $ptype, int $pid) use ($c) {
        $c->prepare("INSERT INTO knowledge_acl (object_type, object_id, principal_type, principal_id, created_datetime)
                     VALUES (?, ?, ?, ?, UTC_TIMESTAMP())")->execute([$otype, $oid, $ptype, $pid]);
    };
    $canRead = function (int $analyst, int $article) use ($c) {
        return knowledgeCanRead($c, KnowledgeViewer::forAnalyst($c, $analyst), $article, ['lifecycle' => 'any']);
    };
    // Static caches inside visibility.php would otherwise answer from the state
    // the first case left behind.
    $reset = function () { knowledgeAclResetCaches(); };

    echo "=== an OPEN folder with a DENY ===\n";
    $openF = $mkFolder(null, 0, 0);
    $openA = $mkArticle($openF);
    $ace('folder', $openF, 'analyst', $alice);
    $reset();
    check('the denied analyst cannot read it', !$canRead($alice, $openA));
    check('POSITIVE CONTROL: everyone else still can', $canRead($bob, $openA),
          'nobody can read it - the filter is hiding everything, so the line above proves nothing');

    echo "\n=== a RESTRICTED folder with one GRANT ===\n";
    $resF = $mkFolder(null, 1, 0);
    $resA = $mkArticle($resF);
    $ace('folder', $resF, 'analyst', $bob);
    $reset();
    check('the granted analyst can read it', $canRead($bob, $resA),
          'the grant did not admit anybody - a Restricted folder that nobody can reach');
    check('everyone else cannot', !$canRead($alice, $resA));

    echo "\n=== a RESTRICTED folder with NO grants admits nobody ===\n";
    $emptyF = $mkFolder(null, 1, 0);
    $emptyA = $mkArticle($emptyF);
    $reset();
    check('not even the author can read it', !$canRead($bob, $emptyA),
          'restricted-to-nobody let somebody through');

    echo "\n=== a RESTRICTED folder with NO rows ANYWHERE still restricts ===\n";
    // ⚠️ THE REGRESSION THIS EXISTS FOR. The resolver has a fast path that skips
    // all the work when nothing is restricted, and it originally asked "does
    // knowledge_acl have any rows?". A Restricted object with an EMPTY list is a
    // real restriction — "restricted to nobody" — carrying no rows at all, so
    // emptying a list (or flipping polarity, which wipes it by design) could
    // leave the table empty, take the fast path, and hand the folder back to
    // everybody. The most restrictive state in the model was the one that turned
    // the guard off.
    //
    // Every other case here has rows in knowledge_acl, so only a case with NONE
    // can catch it. Hence the deletion.
    $c->query("DELETE FROM knowledge_acl");
    $loneF = $mkFolder(null, 1, 0);
    $loneA = $mkArticle($loneF);
    $reset();
    check('nobody can read it even though the access table is completely empty',
          !$canRead($bob, $loneA),
          'the fast path skipped the guard because there were no rows to find');
    // Restore a row so the rest of the file is not accidentally running through
    // that same fast path and passing for the wrong reason.
    $ace('folder', $loneF, 'analyst', $bob);
    $reset();
    check('POSITIVE CONTROL: granting somebody on it lets them in', $canRead($bob, $loneA));

    echo "\n=== a TEAM grant reaches its members ===\n";
    $teamF = $mkFolder(null, 1, 0);
    $teamA = $mkArticle($teamF);
    $ace('folder', $teamF, 'team', $team);
    $reset();
    check('the team member can read it', $canRead($carol, $teamA));
    check('POSITIVE CONTROL: a non-member cannot', !$canRead($alice, $teamA));

    echo "\n=== a USER GROUP grant, and its expiry ===\n";
    $grpF = $mkFolder(null, 1, 0);
    $grpA = $mkArticle($grpF);
    $ace('folder', $grpF, 'user_group', $group);
    $c->prepare("INSERT INTO knowledge_user_group_members (group_id, member_type, member_id, expires_at, created_datetime)
                 VALUES (?, 'analyst', ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 7 DAY), UTC_TIMESTAMP())")->execute([$group, $dave]);
    $reset();
    check('a current member can read it', $canRead($dave, $grpA));
    // The whole reason expires_at exists: the three engineers go home.
    $c->prepare("UPDATE knowledge_user_group_members SET expires_at = DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 DAY) WHERE group_id = ?")->execute([$group]);
    $reset();
    check('an EXPIRED member cannot - the clock takes it away, not a person', !$canRead($dave, $grpA),
          'expired membership still granted access');

    echo "\n=== inheritance: a child folder takes its parent's rules ===\n";
    $parentF = $mkFolder(null, 1, 0);          // restricted, grants Bob
    $childF  = $mkFolder($parentF, 0, 1);      // inherits
    $childA  = $mkArticle($childF);
    $ace('folder', $parentF, 'analyst', $bob);
    $reset();
    check('the parent\'s grant reaches into the child', $canRead($bob, $childA));
    check('POSITIVE CONTROL: someone not granted on the parent cannot', !$canRead($alice, $childA));

    echo "\n=== an article with its OWN rules ignores the folder ===\n";
    $ownF = $mkFolder(null, 0, 0);             // open, denies nobody
    $ownA = $mkArticle($ownF, 1, 0);           // restricted, breaks inheritance
    $ace('article', $ownA, 'analyst', $bob);
    $reset();
    check('the granted analyst can read it', $canRead($bob, $ownA));
    check('everyone else cannot, even though the FOLDER is open', !$canRead($alice, $ownA),
          'the article-level rules were ignored');

    echo "\n=== containers vs filing: an open document inside a closed folder ===\n";
    // Closed folder (grants only Bob) containing a folder that is open to all.
    $closedF = $mkFolder(null, 1, 0);
    $ace('folder', $closedF, 'analyst', $bob);
    $openInside = $mkFolder($closedF, 0, 0);   // open, own rules, denies nobody
    $insideA    = $mkArticle($openInside);
    $c->prepare("DELETE FROM system_settings WHERE setting_key = 'knowledge_folder_permission_model'")->execute();
    $c->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('knowledge_folder_permission_model', 'containers')")->execute();
    $reset();
    check('CONTAINERS: a locked cabinet is locked - Alice cannot reach it', !$canRead($alice, $insideA));
    check('CONTAINERS: POSITIVE CONTROL - Bob, who is granted above, can', $canRead($bob, $insideA));

    $c->prepare("UPDATE system_settings SET setting_value = 'filing' WHERE setting_key = 'knowledge_folder_permission_model'")->execute();
    $reset();
    check('FILING: the nearest rules win, so Alice CAN reach it', $canRead($alice, $insideA),
          'the setting had no effect - both models behaved the same');
    check('FILING: POSITIVE CONTROL - Bob still can too', $canRead($bob, $insideA));

    $c->prepare("UPDATE system_settings SET setting_value = 'containers' WHERE setting_key = 'knowledge_folder_permission_model'")->execute();
    $reset();

    echo "\n=== the rule: an access list NARROWS, it never widens ===\n";
    // An internal article in a folder that grants a PORTAL USER cannot become
    // visible to that portal user - the audience ladder is checked as well.
    $narrowF = $mkFolder(null, 1, 0);
    $narrowA = $mkArticle($narrowF);
    $ace('folder', $narrowF, 'user', 1);
    $ace('folder', $narrowF, 'analyst', $bob);
    $reset();
    $portal = KnowledgeViewer::forPortalUser($c, 1, null);
    check('a portal user granted on an INTERNAL article still cannot read it',
          !knowledgeCanRead($c, $portal, $narrowA, ['lifecycle' => 'any']),
          'the access list widened past the audience - the core rule is broken');
    check('POSITIVE CONTROL: the granted ANALYST can', $canRead($bob, $narrowA));

    echo "\n=== the administrator floor, and its audit trail ===\n";
    // ⚠️ A SEPARATE analyst, created as an admin — NOT one of the four above
    // promoted mid-test. analystIsAdmin() memoises per request (functions.php),
    // so asking about somebody before promoting them caches the "no" and the
    // promotion then appears to do nothing. That is correct behaviour for a web
    // request and a trap for a harness that changes roles in one process.
    $admin = $mkAnalyst('zz-admin');
    $c->prepare("UPDATE analysts SET is_admin = 1 WHERE id = ?")->execute([$admin]);

    $before = (int)$c->query("SELECT COUNT(*) FROM knowledge_audit WHERE action = 'admin_override'")->fetchColumn();
    check('a non-administrator does NOT hold the floor',
          !knowledgeViewerHasAdminFloor($c, KnowledgeViewer::forAnalyst($c, $alice)));
    check('POSITIVE CONTROL: an administrator DOES',
          knowledgeViewerHasAdminFloor($c, KnowledgeViewer::forAnalyst($c, $admin)),
          'nobody holds the floor, so the assertions below prove nothing');

    $reset();
    check('the floor gets an administrator into a restricted-to-nobody folder',
          knowledgeCanRead($c, KnowledgeViewer::forAnalyst($c, $admin), $emptyA, ['lifecycle' => 'any']),
          'the folder is unrecoverable even for an administrator');
    $after = (int)$c->query("SELECT COUNT(*) FROM knowledge_audit WHERE action = 'admin_override'")->fetchColumn();
    check('...and the override is RECORDED', $after === $before + 1,
          "audit rows went from $before to $after - a permission that always passes and leaves no trace");

    // An article the administrator could read anyway must NOT be logged as an
    // override, or the audit fills with noise and the real rows are lost.
    $reset();
    $openForAll = $mkArticle(null);
    knowledgeCanRead($c, KnowledgeViewer::forAnalyst($c, $admin), $openForAll, ['lifecycle' => 'any']);
    $after2 = (int)$c->query("SELECT COUNT(*) FROM knowledge_audit WHERE action = 'admin_override'")->fetchColumn();
    check('an ordinary read by an administrator is NOT logged as an override',
          $after2 === $after, "audit rows went from $after to $after2 - every admin read is being logged");

    $c->rollBack();
    echo "\nrolled back\n";
} catch (Throwable $e) {
    if ($c->inTransaction()) $c->rollBack();
    echo "  FAIL  harness threw: " . $e->getMessage() . "\n    " . $e->getFile() . ':' . $e->getLine() . "\n";
    $fail++;
}

echo "\n$pass passed, $fail failed\n";
exit($fail > 0 ? 1 : 0);
