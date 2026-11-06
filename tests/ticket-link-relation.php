<?php
/* 🔴 NEVER OVER THE WEB. See tests/web-exposure-guard.php. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Ticket links through the LinkRelation enum (4.0).
 *
 *   php tests/ticket-link-relation.php
 *
 * includes/ticket_links.php used strings, a $map array, an if/elseif for the
 * guards and the audit lines, and a switch whose `default:` showed ANY unknown
 * stored type as "related". Those became LinkRelation. This proves the four
 * choices store what they always stored, the guards still refuse, the audit
 * trail still reads the same, and an unknown stored type is left out rather than
 * shown as related.
 *
 * Runs against the real database with two existing tickets, inside ONE
 * transaction that is always rolled back: no link, no audit row survives.
 */

chdir(dirname(__DIR__));
require_once 'config.php';
require_once 'includes/functions.php';
require_once 'includes/ticket_links.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  ok    $what\n"; }
    else       { $fail++; echo "  FAIL  $what" . ($detail !== '' ? "  ($detail)" : '') . "\n"; }
}

echo "Ticket links through LinkRelation (4.0)\n" . str_repeat('=', 60) . "\n";

echo "\nThe enum\n";
ok('stored values unchanged', array_map(fn($c) => $c->value, LinkRelation::cases()) === ['related', 'duplicate', 'parent']);
ok('child_of is the inverse parent', LinkRelation::fromChoice('child_of') === [LinkRelation::Parent, true]);
ok('an unknown choice is refused', LinkRelation::fromChoice('sibling_of') === null);
ok('audit lines, parent', LinkRelation::Parent->auditLines('T-1', 'T-2') === ['Parent of T-2', 'Child of T-1']);
ok('audit lines, duplicate', LinkRelation::Duplicate->auditLines('T-1', 'T-2') === ['Duplicate of T-2', 'Duplicated by T-1']);

$conn = connectToDatabase();
// Admin sees every ticket. Three tickets in one company, none already linked to each other.
$admin = (int)$conn->query("SELECT id FROM analysts WHERE is_admin = 1 AND is_active = 1 ORDER BY id LIMIT 1")->fetchColumn();
// Not deleted (ticketLinkLoad() refuses those), and openable by the admin.
$rows = $conn->query("SELECT id, tenant_id FROM tickets WHERE deleted_datetime IS NULL
                      AND id NOT IN (SELECT source_ticket_id FROM ticket_links UNION SELECT target_ticket_id FROM ticket_links)
                      ORDER BY id DESC LIMIT 200")->fetchAll(PDO::FETCH_ASSOC);
$byTenant = [];
foreach ($rows as $r) {
    if (analystCanAccessTicket($conn, $admin, (int)$r['id'])) $byTenant[(string)$r['tenant_id']][] = (int)$r['id'];
}
$ids = null;
foreach ($byTenant as $list) if (count($list) >= 3) { $ids = array_slice($list, 0, 3); break; }
if (!$admin || !$ids) { echo "  SKIP  needs an admin and three unlinked tickets in one company\n"; exit(2); }
[$a, $b, $c] = $ids;

$conn->beginTransaction();
try {
    echo "\nCreating links (rolled back at the end)\n";
    $r = ticketLinkCreate($conn, $admin, $a, $b, 'parent_of');
    ok('parent_of is created', $r['success'] ?? false, $r['error'] ?? '');
    $stored = $conn->query("SELECT relation_type, source_ticket_id, target_ticket_id FROM ticket_links WHERE (source_ticket_id = $a AND target_ticket_id = $b)")->fetch(PDO::FETCH_ASSOC);
    ok('stored as relation_type parent, a -> b', $stored && $stored['relation_type'] === 'parent', json_encode($stored));
    $audit = $conn->query("SELECT new_value FROM ticket_audit WHERE ticket_id = $a AND field_name = 'Linked ticket' ORDER BY id DESC LIMIT 1")->fetchColumn();
    ok("the source's history says 'Parent of ...'", is_string($audit) && str_starts_with($audit, 'Parent of '), (string)$audit);

    $r = ticketLinkCreate($conn, $admin, $c, $b, 'parent_of');
    ok('GUARD: a child cannot have a second parent', !($r['success'] ?? true) && str_contains($r['error'] ?? '', 'already has a parent'), $r['error'] ?? '');
    $r = ticketLinkCreate($conn, $admin, $b, $a, 'parent_of');
    ok('GUARD: a loop is refused', !($r['success'] ?? true) && str_contains($r['error'] ?? '', 'loop'), $r['error'] ?? '');

    $r = ticketLinkCreate($conn, $admin, $c, $a, 'child_of');
    ok('child_of is created', $r['success'] ?? false, $r['error'] ?? '');
    $stored = $conn->query("SELECT relation_type FROM ticket_links WHERE source_ticket_id = $a AND target_ticket_id = $c")->fetchColumn();
    ok('child_of is stored as the inverse parent (a -> c)', $stored === 'parent', (string)$stored);

    $r = ticketLinkCreate($conn, $admin, $b, $c, 'duplicate_of');
    ok('duplicate_of is created', $r['success'] ?? false, $r['error'] ?? '');
    $r = ticketLinkCreate($conn, $admin, $b, $a, 'duplicate_of');
    ok('GUARD: a duplicate has only one master', !($r['success'] ?? true), $r['error'] ?? '');

    $r = ticketLinkCreate($conn, $admin, $c, $b, 'related');
    ok('related is created', $r['success'] ?? false, $r['error'] ?? '');
    $r = ticketLinkCreate($conn, $admin, $b, $c, 'related');
    ok('GUARD: the reciprocal related link is refused', !($r['success'] ?? true), $r['error'] ?? '');
    $r = ticketLinkCreate($conn, $admin, $a, $b, 'sibling_of');
    ok('GUARD: an unknown choice is refused', ($r['error'] ?? '') === 'Invalid relation type.');

    echo "\nReading them back\n";
    $la = ticketLinksFor($conn, $a);
    ok('a has two children (b, c)', count($la['children']) === 2, json_encode(array_column($la['children'], 'ticket_id')));
    $lb = ticketLinksFor($conn, $b);
    ok('b has parent a', ($lb['parent']['ticket_id'] ?? null) === $a);
    ok('b is a duplicate of c', ($lb['duplicate_of']['ticket_id'] ?? null) === $c);
    $lc = ticketLinksFor($conn, $c);
    ok('c is duplicated by b', array_column($lc['duplicates'], 'ticket_id') === [$b]);
    ok('c is related to b', array_column($lc['related'], 'ticket_id') === [$b]);

    // A row with a type the code does not know: until 4.0 it was shown as related.
    $conn->exec("INSERT INTO ticket_links (source_ticket_id, target_ticket_id, relation_type, created_by_id, created_datetime)
                 VALUES ($a, $c, 'zzbogus', $admin, UTC_TIMESTAMP())");
    $before = error_reporting(); ini_set('error_log', PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null');
    $la2 = ticketLinksFor($conn, $a);
    ok('an unknown stored type is left out, not shown as related', count($la2['related']) === count($la['related']), json_encode($la2['related']));
} catch (Throwable $e) {
    ok('no exception', false, $e->getMessage());
} finally {
    $conn->rollBack();
}
$left = (int)$conn->query("SELECT COUNT(*) FROM ticket_links WHERE source_ticket_id IN ($a, $b, $c) OR target_ticket_id IN ($a, $b, $c)")->fetchColumn();
ok('rolled back: nothing left behind', $left === 0, "$left rows");

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
