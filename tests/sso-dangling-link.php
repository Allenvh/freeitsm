<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * A sign-in link that outlived its account (GH issue #117, follow-up).
 *
 * Reported by the same person as #117: after importing the Core demo data his
 * OIDC account vanished from the admin interface AND became impossible to sign
 * back into, permanently, with "Your account is no longer available."
 *
 * The mechanism, which is what these tests pin down:
 *
 *   1. api/system/import_demo_data.php empties `analysts` (bar 'admin') and
 *      `users` with FOREIGN_KEY_CHECKS off, so the ON DELETE CASCADE on the
 *      two identity tables never fires and the links are left DANGLING.
 *   2. oidc_callback.php resolves a person by (provider, subject) FIRST. A
 *      dangling link wins that lookup, the account load returns null, and the
 *      sign-in dead-ends — because email matching and just-in-time
 *      provisioning both live in the branch PAST it.
 *   3. So re-creating the account by hand does not help either. The link still
 *      points at the old id.
 *
 * The irony worth keeping: had the cascade fired, nobody would have noticed.
 * The link would have gone with the account, the next sign-in would have
 * landed in the JIT branch, and the account would have quietly come back.
 *
 * ⚠️ Touches the database. Every row it creates is prefixed ZZTEST and is
 * removed again in the cleanup at the bottom, including on failure.
 *
 * Run:  php tests/sso-dangling-link.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/oidc.php';
require_once __DIR__ . '/../includes/ldap.php';   // the LDAP path shares the same resolver shape

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

echo "\nDangling SSO sign-in links (#117 follow-up)\n";
echo str_repeat('=', 64) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/** Does a link exist for this (provider, subject)? Mirrors the callback's own lookup. */
function linkTarget(PDO $conn, string $table, string $idCol, int $providerId, string $sub) {
    $stmt = $conn->prepare("SELECT `$idCol` FROM `$table` WHERE provider_id = ? AND subject = ?");
    $stmt->execute([$providerId, $sub]);
    return $stmt->fetchColumn();
}

$providerId = 0;
try {
    // ---- Setup ------------------------------------------------------------
    $conn->prepare(
        "INSERT INTO auth_providers (display_name, issuer_url, client_id)
         VALUES ('ZZTEST Dangling Link', 'https://zztest.invalid/', 'zztest-client')"
    )->execute();
    $providerId = (int)$conn->lastInsertId();

    $conn->prepare(
        "INSERT INTO analysts (username, password_hash, full_name, email, auth_provider_id)
         VALUES ('zztest_analyst', 'x', 'ZZTEST Analyst', 'zztest@zztest.invalid', ?)"
    )->execute([$providerId]);
    $analystId = (int)$conn->lastInsertId();

    $conn->prepare(
        "INSERT INTO users (email, display_name, auth_provider_id)
         VALUES ('zztest-user@zztest.invalid', 'ZZTEST User', ?)"
    )->execute([$providerId]);
    $userId = (int)$conn->lastInsertId();

    $conn->prepare(
        "INSERT INTO analyst_sso_identities (analyst_id, provider_id, subject, email)
         VALUES (?, ?, 'zztest-sub-analyst', 'zztest@zztest.invalid')"
    )->execute([$analystId, $providerId]);

    $conn->prepare(
        "INSERT INTO user_sso_identities (user_id, provider_id, subject, email)
         VALUES (?, ?, 'zztest-sub-user', 'zztest-user@zztest.invalid')"
    )->execute([$userId, $providerId]);

    // A second, HEALTHY link on the same provider. Nothing below may touch it —
    // a "fix" that clears links indiscriminately would sign everyone out.
    $conn->prepare(
        "INSERT INTO users (email, display_name, auth_provider_id)
         VALUES ('zztest-bystander@zztest.invalid', 'ZZTEST Bystander', ?)"
    )->execute([$providerId]);
    $bystanderId = (int)$conn->lastInsertId();
    $conn->prepare(
        "INSERT INTO user_sso_identities (user_id, provider_id, subject, email)
         VALUES (?, ?, 'zztest-sub-bystander', 'zztest-bystander@zztest.invalid')"
    )->execute([$bystanderId, $providerId]);

    // ---- 1. The cascade is correct when it is allowed to run --------------
    // Deleting an account the ordinary way takes its link with it, which is why
    // this has never bitten anyone using the admin interface. The importer is
    // the anomaly, not the schema.
    echo "\nWith foreign key checks ON, deleting an account removes its link:\n";
    $conn->prepare(
        "INSERT INTO users (email, display_name, auth_provider_id)
         VALUES ('zztest-cascade@zztest.invalid', 'ZZTEST Cascade', ?)"
    )->execute([$providerId]);
    $cascadeId = (int)$conn->lastInsertId();
    $conn->prepare(
        "INSERT INTO user_sso_identities (user_id, provider_id, subject, email)
         VALUES (?, ?, 'zztest-sub-cascade', 'zztest-cascade@zztest.invalid')"
    )->execute([$cascadeId, $providerId]);
    $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$cascadeId]);
    ok('ON DELETE CASCADE removes the link with the account',
       linkTarget($conn, 'user_sso_identities', 'user_id', $providerId, 'zztest-sub-cascade') === false,
       'the cascade did not fire — the schema constraint may be missing');

    // ---- 2. Reproduce the orphan exactly as the importer makes it ---------
    echo "\nWith foreign key checks OFF (what the demo importer does), it does not:\n";
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
    $conn->prepare("DELETE FROM analysts WHERE id = ?")->execute([$analystId]);
    $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$userId]);
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1");

    $orphanAnalyst = linkTarget($conn, 'analyst_sso_identities', 'analyst_id', $providerId, 'zztest-sub-analyst');
    $orphanUser    = linkTarget($conn, 'user_sso_identities',    'user_id',    $providerId, 'zztest-sub-user');
    ok('the analyst link survives its deleted analyst', (int)$orphanAnalyst === $analystId,
       'no orphan was produced — the reproduction itself is broken, later results mean nothing');
    ok('the requester link survives its deleted requester', (int)$orphanUser === $userId,
       'no orphan was produced — the reproduction itself is broken, later results mean nothing');

    // And this is the branch that traps the person: the lookup succeeds, so the
    // callback commits to "returning user" and never reaches email match / JIT.
    $stmt = $conn->prepare("SELECT COUNT(*) FROM analysts WHERE id = ?");
    $stmt->execute([$analystId]);
    ok('the account it points at is genuinely gone', (int)$stmt->fetchColumn() === 0);

    // ---- 3. The heal ------------------------------------------------------
    echo "\nClearing a dangling link puts the person back on the first-time path:\n";
    ssoClearDanglingLink($conn, 'analyst_sso_identities', $providerId, 'zztest-sub-analyst');
    ssoClearDanglingLink($conn, 'user_sso_identities',    $providerId, 'zztest-sub-user');

    ok('the dangling analyst link is gone',
       linkTarget($conn, 'analyst_sso_identities', 'analyst_id', $providerId, 'zztest-sub-analyst') === false);
    ok('the dangling requester link is gone',
       linkTarget($conn, 'user_sso_identities', 'user_id', $providerId, 'zztest-sub-user') === false);

    // ---- 4. Negative control ---------------------------------------------
    echo "\nNegative control — a healthy link on the same provider is untouched:\n";
    ok('the bystander keeps their link',
       (int)linkTarget($conn, 'user_sso_identities', 'user_id', $providerId, 'zztest-sub-bystander') === $bystanderId,
       'the clear is too broad and would sign healthy accounts out');

    // ---- 5. The LDAP path, which had the same fault -----------------------
    // The sweep for #1296 found ldapResolveAnalyst()/ldapResolveUser() resolving
    // by (provider, subject) first and dead-ending the same way, so it was FOUR
    // branches, not two. This drives the real function: no network is involved
    // once we hand it the directory attributes an authenticated bind returns.
    echo "\nThe LDAP resolver falls through to the email match, rather than dead-ending:\n";
    $conn->prepare(
        "INSERT INTO analysts (username, password_hash, full_name, email, auth_provider_id, is_active)
         VALUES ('zztest_ldap', 'x', 'ZZTEST LDAP', 'zztest-ldap@zztest.invalid', ?, 1)"
    )->execute([$providerId]);
    $ldapAnalystId = (int)$conn->lastInsertId();
    $conn->prepare(
        "INSERT INTO analyst_sso_identities (analyst_id, provider_id, subject, email)
         VALUES (?, ?, 'zztest-sub-ldap', 'zztest-ldap@zztest.invalid')"
    )->execute([$ldapAnalystId, $providerId]);

    // Delete the analyst the way the importer used to, leaving the link behind,
    // then re-create the same person as an administrator would.
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
    $conn->prepare("DELETE FROM analysts WHERE id = ?")->execute([$ldapAnalystId]);
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
    ok('the LDAP link is left dangling by the same manoeuvre',
       (int)linkTarget($conn, 'analyst_sso_identities', 'analyst_id', $providerId, 'zztest-sub-ldap') === $ldapAnalystId);

    $conn->prepare(
        "INSERT INTO analysts (username, password_hash, full_name, email, auth_provider_id, is_active)
         VALUES ('zztest_ldap2', 'x', 'ZZTEST LDAP Recreated', 'zztest-ldap@zztest.invalid', ?, 1)"
    )->execute([$providerId]);
    $recreatedId = (int)$conn->lastInsertId();

    $provider = ['id' => $providerId];
    $result   = ldapResolveAnalyst($conn, $provider, [
        'guid'     => 'zztest-sub-ldap',
        'email'    => 'zztest-ldap@zztest.invalid',
        'username' => 'zztest_ldap2',
        'name'     => 'ZZTEST LDAP Recreated',
    ]);
    ok('the re-created analyst signs in', !empty($result['ok']),
       'got: ' . json_encode($result) . ' — before the fix this returned "Your account is inactive"');
    ok('and resolves to the RE-CREATED account, not the deleted id',
       ($result['analyst_id'] ?? 0) === $recreatedId,
       'resolved to ' . ($result['analyst_id'] ?? 'nothing') . ', expected ' . $recreatedId);

    // The assertion that makes the CLEAR load-bearing rather than just the
    // fall-through. ldapResolveAnalyst() re-links inside a try/catch that
    // swallows a unique-key failure, so without the clear the stale row simply
    // survives — the person signs in, and the link still points at the dead id
    // for ever, with nothing reporting it. Repairing the link is the fix.
    ok('and the link now points at the live account, not the dead id',
       (int)linkTarget($conn, 'analyst_sso_identities', 'analyst_id', $providerId, 'zztest-sub-ldap') === $recreatedId,
       'the stale link survived — the re-link INSERT swallows its own failure, so this is the only thing that catches it');

    // ---- 6. The table name is not a free-text hole ------------------------
    echo "\nThe table argument is checked, not interpolated blindly:\n";
    $threw = false;
    try { ssoClearDanglingLink($conn, 'analysts', $providerId, 'zztest-sub-bystander'); }
    catch (Exception $e) { $threw = true; }
    ok('an unexpected table name is refused', $threw,
       'the helper would run a DELETE against any table a caller named');

} finally {
    // ---- Cleanup ----------------------------------------------------------
    if ($providerId) {
        try {
            $conn->prepare("DELETE FROM user_sso_identities    WHERE provider_id = ?")->execute([$providerId]);
            $conn->prepare("DELETE FROM analyst_sso_identities WHERE provider_id = ?")->execute([$providerId]);
            $conn->prepare("DELETE FROM users    WHERE email LIKE 'zztest%@zztest.invalid'")->execute();
            $conn->prepare("DELETE FROM analysts WHERE username LIKE 'zztest_%'")->execute();
            $conn->prepare("DELETE FROM auth_providers WHERE id = ?")->execute([$providerId]);
        } catch (Exception $e) {
            echo "\n  ⚠️  cleanup failed: " . $e->getMessage() . "\n";
            echo "      remove rows with provider_id {$providerId} / prefix ZZTEST by hand.\n";
        }
    }
}

echo "\n" . str_repeat('=', 64) . "\n";
echo "  {$pass} passed, {$fail} failed\n\n";
exit($fail === 0 ? 0 : 1);
