<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Analyst provisioning (3.5.0, PR #174) - the rules in includes/services/analysts.php.
 *
 *   php tests/analysts-provisioning.php
 *
 * A new analyst starts with nothing; emails are unique; access parts replace
 * only what is sent; keys are validated; capabilities live on one managed role
 * that leaves hand-made roles alone and goes with its analyst; nobody removes or
 * deactivates the last administrator or themselves. Every refusal has a positive
 * control.
 *
 * The REST adapter's KEY rule (administrator + all companies) is checked over
 * HTTP in the release test list - it lives in api/v1/resources/analysts.php.
 *
 * Runs on the real database inside a transaction that is ALWAYS rolled back.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/analysts.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
function err(callable $fn): ?string {
    try { $fn(); return null; } catch (ServiceError $e) { return $e->kind . ': ' . $e->getMessage(); }
}

echo "\nAnalyst provisioning (PR #174)\n" . str_repeat('=', 70) . "\n";
$conn = connectToDatabase();
if (AnalystsService::managedRoleId($conn, 0) === null) {
    try { $conn->query("SELECT managed_for_analyst_id FROM rbac_roles LIMIT 0"); }
    catch (Throwable $e) { echo "  SKIP  run Database Verification first\n"; exit(0); }
}
$multi = isMultiTenant($conn);
$actor = new ActorContext(actorId: 1, companyScope: null, source: 'api');

$conn->beginTransaction();
try {
    echo "\n-- Create\n";
    ok('email is required', str_contains((string)err(fn() => AnalystsService::create($conn, $actor, [])), 'required'));
    ok('a bad email is refused', str_contains((string)err(fn() => AnalystsService::create($conn, $actor, ['email' => 'nope'])), 'valid email'));
    ok('an unknown provider is refused', str_contains((string)err(fn() => AnalystsService::create($conn, $actor, ['email' => 'zz174a@example.invalid', 'auth_provider_id' => 999999])), 'sign-in provider'));
    $A = AnalystsService::create($conn, $actor, ['email' => 'ZZ174a@Example.invalid', 'full_name' => 'ZZ One']);
    $row = AnalystsService::load($conn, $A);
    ok('created, email stored lower-case', $row['email'] === 'zz174a@example.invalid', $row['email']);
    ok('username from the email', $row['username'] === 'zz174a', $row['username']);
    $acc = AnalystsService::access($conn, $A, false);
    ok('starts with no modules', $acc['all_modules'] === false && $acc['modules'] === []);
    ok('starts not an admin', $acc['is_admin'] === false);
    ok('starts with ' . ($multi ? 'no companies (multi-company)' : 'the one company'), $acc['all_companies'] === !$multi && $acc['companies'] === []);
    ok('the local password is unusable', !password_verify('', $row['password_hash']) && strlen($row['password_hash']) > 20);
    ok('a duplicate email is refused, whatever its case', str_contains((string)err(fn() => AnalystsService::create($conn, $actor, ['email' => 'zz174A@example.INVALID'])), 'already uses'));
    $B = AnalystsService::create($conn, $actor, ['email' => 'zz174b@example.invalid', 'username' => 'zz174a']);
    ok('a taken username gets a number', AnalystsService::load($conn, $B)['username'] === 'zz174a1');

    echo "\n-- Update\n";
    AnalystsService::update($conn, $actor, $A, ['full_name' => 'ZZ One Renamed', 'is_active' => false]);
    $row = AnalystsService::load($conn, $A);
    ok('name and active flag change', $row['full_name'] === 'ZZ One Renamed' && (int)$row['is_active'] === 0);
    ok('an empty update is refused', str_contains((string)err(fn() => AnalystsService::update($conn, $actor, $A, [])), 'at least one'));
    ok('is_active must be a boolean', str_contains((string)err(fn() => AnalystsService::update($conn, $actor, $A, ['is_active' => 'maybe'])), 'true or false'));
    ok('another analyst\'s email is refused', str_contains((string)err(fn() => AnalystsService::update($conn, $actor, $A, ['email' => 'zz174b@example.invalid'])), 'already uses'));
    ok('you cannot deactivate yourself', str_contains((string)err(fn() => AnalystsService::update($conn, new ActorContext(actorId: $B, companyScope: null, source: 'api'), $B, ['is_active' => false])), 'yourself'));
    AnalystsService::update($conn, $actor, $A, ['is_active' => true]);
    ok('(control) and back on', (int)AnalystsService::load($conn, $A)['is_active'] === 1);
    ok('an unknown analyst is not found', str_contains((string)err(fn() => AnalystsService::update($conn, $actor, 99999999, ['full_name' => 'x'])), 'not_found'));

    echo "\n-- Access\n";
    ok('nothing sent is refused', str_contains((string)err(fn() => AnalystsService::setAccess($conn, $actor, $A, [])), 'at least one'));
    ok('an unknown module is refused', str_contains((string)err(fn() => AnalystsService::setAccess($conn, $actor, $A, ['modules' => ['tickets', 'nope']])), 'nope'));
    ok('System is not a module', str_contains((string)err(fn() => AnalystsService::setAccess($conn, $actor, $A, ['modules' => ['system']])), 'is_admin'));
    ok('an unknown capability is refused', str_contains((string)err(fn() => AnalystsService::setAccess($conn, $actor, $A, ['capabilities' => ['tickets.fly']])), 'tickets.fly'));
    ok('an unknown company is refused', str_contains((string)err(fn() => AnalystsService::setAccess($conn, $actor, $A, ['companies' => [99999999]])), '99999999'));
    $cap = capAll() ? array_values(capAll())[0] : null;
    $company = (int)$conn->query("SELECT id FROM tenants ORDER BY id LIMIT 1")->fetchColumn();
    AnalystsService::setAccess($conn, $actor, $A, ['modules' => ['tickets', 'knowledge', 'tickets'], 'companies' => [$company], 'capabilities' => $cap ? [$cap, $cap] : []]);
    $acc = AnalystsService::access($conn, $A, false);
    ok('modules set (duplicates dropped)', $acc['modules'] === ['knowledge', 'tickets'], json_encode($acc['modules']));
    ok('companies set', $acc['companies'] === [$company]);
    if ($cap) {
        ok('capability held on one managed role', $acc['capabilities'] === [$cap] && AnalystsService::managedRoleId($conn, $A) !== null);
        $roleId = AnalystsService::managedRoleId($conn, $A);
        $n = (int)$conn->query("SELECT COUNT(*) FROM rbac_analyst_roles WHERE analyst_id = $A AND role_id = $roleId")->fetchColumn();
        ok('...and assigned to them', $n === 1);
    }
    AnalystsService::setAccess($conn, $actor, $A, ['all_modules' => true]);
    $acc = AnalystsService::access($conn, $A, false);
    ok('a part not sent stays as it was', $acc['all_modules'] === true && $acc['modules'] === ['knowledge', 'tickets'] && $acc['companies'] === [$company]);

    // A role given by hand is never touched.
    $conn->prepare("INSERT INTO rbac_roles (name, is_active) VALUES ('ZZ174 hand role', 1)")->execute();
    $hand = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO rbac_analyst_roles (analyst_id, role_id) VALUES (?, ?)")->execute([$A, $hand]);
    if ($cap) $conn->prepare("INSERT INTO rbac_role_capabilities (role_id, capability_key) VALUES (?, ?)")->execute([$hand, $cap]);
    AnalystsService::setAccess($conn, $actor, $A, ['capabilities' => []]);
    $still = (int)$conn->query("SELECT COUNT(*) FROM rbac_analyst_roles WHERE analyst_id = $A AND role_id = $hand")->fetchColumn();
    ok('emptying the API capabilities leaves a hand-assigned role alone', $still === 1 && AnalystsService::access($conn, $A, false)['capabilities'] === []);
    if ($cap) {
        $eff = AnalystsService::access($conn, $A, true)['effective'];
        ok('effective still counts the hand-assigned role', in_array($cap, $eff['capabilities'], true));
    }

    echo "\n-- Administrators\n";
    AnalystsService::setAccess($conn, $actor, $A, ['is_admin' => true]);
    ok('made an administrator', AnalystsService::access($conn, $A, false)['is_admin'] === true);
    ok('you cannot remove your own admin', str_contains((string)err(fn() => AnalystsService::setAccess($conn, new ActorContext(actorId: $A, companyScope: null, source: 'api'), $A, ['is_admin' => false])), 'your own'));
    AnalystsService::setAccess($conn, $actor, $A, ['is_admin' => false]);
    ok('(control) another admin can', AnalystsService::access($conn, $A, false)['is_admin'] === false);
    // The last active administrator: make $A the only one for a moment.
    $others = $conn->query("SELECT id FROM analysts WHERE is_admin = 1 AND is_active = 1")->fetchAll(PDO::FETCH_COLUMN);
    AnalystsService::setAccess($conn, $actor, $A, ['is_admin' => true]);
    $conn->exec("UPDATE analysts SET is_admin = 0 WHERE id IN (" . implode(',', array_map('intval', $others ?: [0])) . ")");
    ok('the last admin cannot lose admin', str_contains((string)err(fn() => AnalystsService::setAccess($conn, $actor, $A, ['is_admin' => false])), 'last active administrator'));
    ok('nor be deactivated', str_contains((string)err(fn() => AnalystsService::update($conn, $actor, $A, ['is_active' => false])), 'last active administrator'));
    AnalystsService::setAccess($conn, $actor, $B, ['is_admin' => true]);
    AnalystsService::update($conn, $actor, $A, ['is_active' => false]);
    ok('(control) with another admin, they can be', (int)AnalystsService::load($conn, $A)['is_active'] === 0);

    echo "\n-- Deleting the analyst\n";
    if ($cap) AnalystsService::setAccess($conn, $actor, $B, ['capabilities' => [$cap]]);
    $roleB = AnalystsService::managedRoleId($conn, $B);
    $conn->prepare("DELETE FROM analysts WHERE id = ?")->execute([$B]);
    $left = $roleB ? (int)$conn->query("SELECT COUNT(*) FROM rbac_roles WHERE id = $roleB")->fetchColumn() : 0;
    ok('their managed role goes with them', $left === 0);
} catch (Throwable $e) {
    $fail++;
    echo "  FAIL  unexpected: " . get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine() . "\n";
} finally {
    if ($conn->inTransaction()) $conn->rollBack();
}

echo str_repeat('-', 70) . "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
