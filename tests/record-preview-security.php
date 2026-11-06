<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Record previews (#91) — the security boundary.
 *
 * Ed asked whether a preview is "rock solid security wise — for example is
 * there a way a hacker could call the preview function unauthenticated". This
 * file is the answer, written as checks rather than as a claim.
 *
 * 🔴 A preview is a READ of a record the reader may not be entitled to. It is
 * reached from links, and a link can point anywhere. So the interesting question
 * is never "does it work" but "what does it refuse, and does the refusal say
 * anything it should not".
 *
 * ⚠️ The tenancy section INSERTS a restriction so there is something to test —
 * this install has an empty analyst_tenant_access, meaning nobody is limited.
 * It runs inside a transaction that is ALWAYS rolled back, so nothing survives
 * the run. Nothing here is committed, ever.
 *
 * Run:  php tests/record-preview-security.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/i18n.php';
require_once __DIR__ . '/../includes/record_preview.php';
I18n::initFromSession();

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

echo "\nRecord previews — security boundary (#91)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$admin = 1;

// ── 1. Who is asking ────────────────────────────────────────────────────────
echo "\nAn analyst id that is not a real analyst:\n";
$aTicket = (int)$conn->query("SELECT id FROM tickets ORDER BY id DESC LIMIT 1")->fetchColumn();
foreach ([0, -1, 999999999] as $ghost) {
    ok("analyst {$ghost} gets nothing", recordPreview($conn, $ghost, 'ticket', $aTicket) === null);
}
ok('POSITIVE CONTROL: a real analyst does get it',
   recordPreview($conn, $admin, 'ticket', $aTicket) !== null);

// ── 2. The type is a whitelist, not a table name ────────────────────────────
echo "\nThe type cannot be steered anywhere it was not meant to go:\n";
foreach (['users', 'analysts', 'analyst; DROP TABLE tickets', '../../etc/passwd',
          'Ticket', 'TICKET', '', ' ticket'] as $t) {
    ok("type " . var_export($t, true) . " is refused",
        recordPreview($conn, $admin, $t, $aTicket) === null);
}
ok('POSITIVE CONTROL: the exact type still works',
   recordPreview($conn, $admin, 'ticket', $aTicket) !== null);

// ── 3. Company isolation ────────────────────────────────────────────────────
//
// 🔴 Inside a transaction that is always rolled back. It grants a non-admin
// analyst access to ONE company so there is a restriction to test against, then
// throws the grant away. See the header.
echo "\nCompany isolation (a temporary restriction, rolled back):\n";

$byTenant = $conn->query(
    "SELECT tenant_id, MIN(id) AS id FROM tickets WHERE tenant_id IS NOT NULL GROUP BY tenant_id"
)->fetchAll(PDO::FETCH_KEY_PAIR);

// ⚠️ is_admin = 0 IS NOT ENOUGH. The first run of this file picked jsmith, who
// is not an admin but DOES carry can_access_all_tenants = 1 — and every active
// analyst on a fresh install does. So the test granted one company, the analyst
// saw all of them anyway, and it reported a cross-company leak that was purely
// its own doing. The flag is cleared inside the transaction below, along with
// the grant, and both are rolled back.
$nonAdmin = (int)$conn->query(
    "SELECT id FROM analysts WHERE is_active = 1 AND (is_admin = 0 OR is_admin IS NULL) ORDER BY id LIMIT 1"
)->fetchColumn();

if (count($byTenant) < 2 || !$nonAdmin) {
    echo "  SKIP  needs a non-admin analyst and tickets in two companies\n";
} else {
    $tenantIds = array_keys($byTenant);
    $mine      = (int)$tenantIds[0];
    $theirs    = (int)$tenantIds[1];

    $conn->beginTransaction();
    try {
        // Take away all-access, then grant exactly one company.
        $conn->prepare("UPDATE analysts SET can_access_all_tenants = 0 WHERE id = ?")
             ->execute([$nonAdmin]);
        $conn->prepare("INSERT INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?)")
             ->execute([$nonAdmin, $mine]);

        // 🔴 getAccessibleTenantIds() caches per analyst for the life of the
        // process. Anything that asked about this analyst BEFORE the grant would
        // have pinned the old answer, and the assertions below would be testing
        // a cached lie rather than the database. Nothing above touches them —
        // and this comment is why it must stay that way.

        ok("analyst {$nonAdmin} previews a ticket in their own company",
            recordPreview($conn, $nonAdmin, 'ticket', (int)$byTenant[$mine]) !== null,
            'the restriction locked them out of their OWN company');

        ok("…and gets NOTHING for a ticket in another company",
            recordPreview($conn, $nonAdmin, 'ticket', (int)$byTenant[$theirs]) === null,
            'CROSS-COMPANY LEAK');

        // The refusal must be the same shape as a record that is simply absent,
        // or the difference itself confirms the record exists.
        ok('…and that refusal is identical to one for a non-existent ticket',
            recordPreview($conn, $nonAdmin, 'ticket', (int)$byTenant[$theirs])
            === recordPreview($conn, $nonAdmin, 'ticket', 999999999));

        ok('POSITIVE CONTROL: an administrator can still see both',
            recordPreview($conn, $admin, 'ticket', (int)$byTenant[$mine]) !== null
            && recordPreview($conn, $admin, 'ticket', (int)$byTenant[$theirs]) !== null);
    } finally {
        $conn->rollBack();
    }

    // ⚠️ Prove the rollback actually happened, for BOTH changes. A test that
    // quietly leaves a permission grant — or strips an analyst's all-access —
    // is worse than no test at all.
    $stmt = $conn->prepare("SELECT COUNT(*) FROM analyst_tenant_access WHERE analyst_id = ?");
    $stmt->execute([$nonAdmin]);
    ok('the temporary grant was rolled back and no longer exists',
        (int)$stmt->fetchColumn() === 0,
        'A GRANT WAS LEFT BEHIND — remove it by hand');

    $stmt = $conn->prepare("SELECT can_access_all_tenants FROM analysts WHERE id = ?");
    $stmt->execute([$nonAdmin]);
    ok("analyst {$nonAdmin}'s all-access flag was restored by the rollback",
        (int)$stmt->fetchColumn() === 1,
        'THE ANALYST LOST ALL-ACCESS — set can_access_all_tenants = 1 by hand');
}

// ── 4. Nothing reaches the browser that could execute ───────────────────────
//
// The card renders a status colour into a style attribute. The value comes from
// settings rather than from a visitor, but it is still the one field that lands
// somewhere other than as text.
echo "\nWhat a preview is allowed to contain:\n";
$colours = [];
foreach (['ticket_statuses', 'ticket_priorities', 'task_statuses', 'change_statuses',
          'problem_statuses'] as $tbl) {
    try {
        foreach ($conn->query("SELECT colour FROM {$tbl} WHERE colour IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN) as $c) {
            $colours[] = [$tbl, (string)$c];
        }
    } catch (Exception $e) { /* table without a colour column — not all have one */ }
}
$bad = [];
foreach ($colours as [$tbl, $c]) {
    // Anything that is not a plain colour could smuggle a second CSS
    // declaration into the style attribute — url(), an extra semicolon, a
    // closing quote.
    if (!preg_match('/^(#[0-9a-fA-F]{3,8}|[a-zA-Z]+|rgba?\([0-9,.\s%]+\))$/', trim($c))) {
        $bad[] = "{$tbl}: " . var_export($c, true);
    }
}
ok('every stored status colour is a plain colour', $bad === [], implode('; ', $bad));
ok('POSITIVE CONTROL: the check rejects a crafted one',
   !preg_match('/^(#[0-9a-fA-F]{3,8}|[a-zA-Z]+|rgba?\([0-9,.\s%]+\))$/',
               'red;background-image:url(http://evil.example/beacon)'));

// …and rpField() itself refuses to pass one on, so a colour that gets into the
// settings tables later cannot ride into a style attribute.
echo "\nA colour that is not a colour never reaches the card:\n";
foreach ([
    'red;background-image:url(http://evil.example/beacon)',
    '#fff;position:fixed;top:0;left:0;width:100vw;height:100vw',
    'url(http://evil.example/x)',
    'expression(alert(1))',
    '"><script>alert(1)</script>',
] as $crafted) {
    $f = rpField('Status', 'Open', $crafted);
    ok('dropped: ' . substr($crafted, 0, 34), !isset($f['colour']),
        'it came back as ' . var_export($f['colour'] ?? null, true));
}
ok('POSITIVE CONTROL: a real hex colour is kept',
   (rpField('Status', 'Open', '#2563eb')['colour'] ?? null) === '#2563eb');
ok('POSITIVE CONTROL: a named colour is kept',
   (rpField('Status', 'Open', 'rebeccapurple')['colour'] ?? null) === 'rebeccapurple');

// A knowledge lead is the only free text that is not a short field.
$art = (int)$conn->query("SELECT id FROM knowledge_articles ORDER BY id DESC LIMIT 1")->fetchColumn();
if ($art) {
    $p = recordPreview($conn, $admin, 'knowledge_article', $art);
    ok('an article lead carries no markup at all',
        !str_contains((string)($p['lead'] ?? ''), '<'),
        'raw HTML reached the preview');
}

echo str_repeat('=', 70) . "\n  {$pass} passed, {$fail} failed\n\n";
exit($fail === 0 ? 0 : 1);
