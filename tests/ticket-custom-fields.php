<?php
/* 🔴 NEVER OVER THE WEB - see tests/README.md. This one writes to the real
   tables: two ticket types, five custom fields and one ticket, all named ZZTF,
   removed at the end even on failure. The ticket is INSERTed directly, so no
   workflow, notification or email ever fires for it. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Custom ticket fields (discussion #179): which fields a ticket has, the
 * required rules when logging and closing, type changes hiding (never deleting)
 * answers, the history, the settings rules, and the portal's view.
 *
 * Run:  php tests/ticket-custom-fields.php
 */

chdir(__DIR__ . '/..');
require_once 'config.php';
require_once 'includes/functions.php';
require_once 'includes/tenancy.php';
require_once 'includes/services/tickets.php';
require_once 'includes/self_service_ticket_types.php';

$pass = 0; $fail = 0;
function is_(string $what, $got, $want): void {
    global $pass, $fail;
    if ($got === $want) { $pass++; printf("  PASS  %-66s\n", $what); }
    else { $fail++; printf("  FAIL  %-66s got %s, wanted %s\n", $what, json_encode($got), json_encode($want)); }
}
/** The ServiceError slug a call throws, or 'ok'. */
function throws(callable $fn): string {
    try { $fn(); return 'ok'; } catch (ServiceError $e) { return $e->errorCode; }
}

$conn = connectToDatabase();
echo "\nCustom ticket fields (#179)\n" . str_repeat('=', 78) . "\n";
if (!TicketFieldsService::schemaReady($conn)) { echo "  SKIP  tables not created - run Database Verification\n\n"; exit(0); }

$admin = (int)$conn->query("SELECT id FROM analysts WHERE is_admin = 1 AND is_active = 1 ORDER BY id LIMIT 1")->fetchColumn();
$ctx = new ActorContext($admin, null, 'ui', 'en', 'ZZTF Admin');
// Fields are created in the admin's working company (or shared); the ticket lives there too.
$active = isMultiTenant($conn) ? getActiveTenantId($conn, $admin) : null;
$tenant = $active ?? (int)getDefaultTenantId($conn);

$types = []; $fields = []; $ticket = null;
try {
    foreach (['starter' => 'ZZTF Starter', 'other' => 'ZZTF Other'] as $k => $name) {
        $conn->prepare("INSERT INTO ticket_types (name, is_active, tenant_id) VALUES (?, 1, NULL)")->execute([$name]);
        $types[$k] = (int)$conn->lastInsertId();
    }
    $mk = function (array $in) use ($conn, $ctx, &$fields) {
        $r = TicketFieldsService::saveField($conn, $ctx, $in);
        $fields[] = $r['id'];
        return $r['id'];
    };
    $fStart  = $mk(['label' => 'ZZTF Start date', 'field_type' => 'date', 'config' => ['date_mode' => 'date'], 'all_types' => 0,
                    'type_ids' => [$types['starter']], 'required_on_create' => 1, 'ask_requester' => 1]);
    $fUser   = $mk(['label' => 'ZZTF Username', 'field_type' => 'text', 'all_types' => 0, 'type_ids' => [$types['starter']], 'required_on_close' => 1]);
    $fLaptop = $mk(['label' => 'ZZTF Laptop', 'field_type' => 'dropdown', 'options' => ['Small', 'Large', 'Small'], 'all_types' => 1]);
    $fRemote = $mk(['label' => 'ZZTF Remote', 'field_type' => 'boolean', 'all_types' => 0, 'type_ids' => [$types['starter']]]);
    $fMail   = $mk(['label' => 'ZZTF Manager email', 'field_type' => 'email', 'all_types' => 1]);
    $keys = fn(array $defs) => array_values(array_filter(array_keys($defs), fn($k) => str_starts_with($k, 'zztf_')));

    echo "\nWhich fields a ticket has:\n";
    is_('a New starter has all five, in order', $keys(TicketFieldsService::fieldsFor($conn, $tenant, $types['starter'])),
        ['zztf_start_date', 'zztf_username', 'zztf_laptop', 'zztf_remote', 'zztf_manager_email']);
    is_('another type has only the every-type fields', $keys(TicketFieldsService::fieldsFor($conn, $tenant, $types['other'])),
        ['zztf_laptop', 'zztf_manager_email']);
    is_('no type at all: only the every-type fields', $keys(TicketFieldsService::fieldsFor($conn, $tenant, null)),
        ['zztf_laptop', 'zztf_manager_email']);
    is_('the requester is asked only the field marked for them', $keys(TicketFieldsService::fieldsFor($conn, $tenant, $types['starter'], 'requester')),
        ['zztf_start_date']);
    is_('a repeated dropdown option is stored once', TicketFieldsService::fieldsFor($conn, $tenant, null)['zztf_laptop']['options'], ['Small', 'Large']);

    echo "\nRequired when logging, and the type rules:\n";
    $defs = TicketFieldsService::fieldsFor($conn, $tenant, $types['starter']);
    is_('a New starter without its start date is refused', throws(fn() => TicketFieldsService::validate($conn, $defs, ['zztf_username' => 'x'], true)), 'missing_field');
    is_('...and with it, accepted', throws(fn() => TicketFieldsService::validate($conn, $defs, ['zztf_start_date' => '2026-11-03'], true)), 'ok');
    is_('a dropdown answer that is not an option is refused', throws(fn() => TicketFieldsService::validate($conn, $defs, ['zztf_start_date' => '2026-11-03', 'zztf_laptop' => 'Huge'], true)), 'invalid_field');
    is_('an email field refuses something that is not one', throws(fn() => TicketFieldsService::validate($conn, $defs, ['zztf_start_date' => '2026-11-03', 'zztf_manager_email' => 'bob'], true)), 'invalid_field');
    is_('a field this ticket does not have is refused', throws(fn() => TicketFieldsService::validate($conn, $defs, ['zztf_start_date' => '2026-11-03', 'nope' => 1], true)), 'invalid_field');
    $before = (int)$conn->query("SELECT COUNT(*) FROM tickets")->fetchColumn();
    is_('createTicket refuses before writing anything', throws(fn() => TicketsService::createTicket($conn, $ctx, $tenant, [
        'subject' => 'ZZTF never made', 'requester_email' => 'zztf@zztf.invalid', 'ticket_type_id' => $types['starter'], 'custom_fields' => [],
    ], $admin, 'ZZTF')), 'missing_field');
    is_('...and no ticket was created', (int)$conn->query("SELECT COUNT(*) FROM tickets")->fetchColumn(), $before);

    echo "\nAnswers on a ticket:\n";
    $status = (int)$conn->query("SELECT id FROM ticket_statuses WHERE is_closed = 0 ORDER BY display_order, id LIMIT 1")->fetchColumn();
    $conn->prepare("INSERT INTO tickets (ticket_number, subject, status_id, ticket_type_id, tenant_id, created_datetime, updated_datetime)
                    VALUES ('ZZTF-1', 'ZZTF ticket', ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())")->execute([$status, $types['starter'], $tenant]);
    $ticket = (int)$conn->lastInsertId();
    TicketsService::updateTicket($conn, $ctx, $ticket, ['custom_fields' => [
        'zztf_start_date' => '2026-11-03', 'zztf_laptop' => 'Large', 'zztf_remote' => '1', 'zztf_manager_email' => 'boss@example.com',
    ]], false);
    $row = TicketsService::loadTicket($conn, $ctx, $ticket);
    $shown = array_column(TicketFieldsService::forTicket($conn, $row), 'display', 'key');
    is_('the answers are stored and shown as a person reads them',
        [$shown['zztf_start_date'], $shown['zztf_laptop'], $shown['zztf_remote'], $shown['zztf_username']],
        ['2026-11-03', 'Large', 'Yes', null]);
    is_('each change is in the ticket history, by name', (int)$conn->query("SELECT COUNT(*) FROM ticket_audit WHERE ticket_id = $ticket AND field_name LIKE 'ZZTF %'")->fetchColumn(), 4);
    is_('a field required when logging cannot be cleared later', throws(fn() => TicketsService::updateTicket($conn, $ctx, $ticket, ['custom_fields' => ['zztf_start_date' => '']], false)), 'missing_field');
    is_('an optional one can', throws(fn() => TicketsService::updateTicket($conn, $ctx, $ticket, ['custom_fields' => ['zztf_remote' => '']], false)), 'ok');

    echo "\nChanging the type hides, never deletes:\n";
    TicketsService::updateTicket($conn, $ctx, $ticket, ['ticket_type_id' => $types['other']], false);
    $row = TicketsService::loadTicket($conn, $ctx, $ticket);
    is_('the new type shows only its own fields', array_values(array_filter(array_column(TicketFieldsService::forTicket($conn, $row), 'key'), fn($k) => str_starts_with($k, 'zztf_'))),
        ['zztf_laptop', 'zztf_manager_email']);
    is_('...while the start date is still in the database', (int)$conn->query("SELECT COUNT(*) FROM ticket_field_values WHERE ticket_id = $ticket AND field_id = $fStart")->fetchColumn(), 1);
    TicketsService::updateTicket($conn, $ctx, $ticket, ['ticket_type_id' => $types['starter']], false);
    $row = TicketsService::loadTicket($conn, $ctx, $ticket);
    is_('changing it back brings the answer back', array_column(TicketFieldsService::forTicket($conn, $row), 'display', 'key')['zztf_start_date'], '2026-11-03');

    echo "\nRequired before closing:\n";
    $missing = MandatoryFieldsService::missing($conn, $tenant, $row);
    is_('the username is listed as missing at close', $missing['cf:zztf_username'] ?? null, 'ZZTF Username');
    is_('...but not when the same save fills it in', isset(MandatoryFieldsService::missing($conn, $tenant, $row, ['zztf_username' => 'amorgan'])['cf:zztf_username']), false);

    echo "\nDesigning fields:\n";
    is_('a kind cannot change once tickets have answered it', throws(fn() => TicketFieldsService::saveField($conn, $ctx, [
        'id' => $fLaptop, 'label' => 'ZZTF Laptop', 'field_type' => 'text', 'all_types' => 1])), 'type_locked');
    is_('a field needs types, or every type', throws(fn() => TicketFieldsService::saveField($conn, $ctx, ['label' => 'ZZTF Nowhere', 'field_type' => 'text', 'all_types' => 0])), 'missing_field');
    $dupe = $mk(['label' => 'ZZTF Laptop', 'field_type' => 'text', 'all_types' => 1]);
    is_('a second field with the same name gets its own key', $conn->query("SELECT field_key FROM ticket_fields WHERE id = $dupe")->fetchColumn(), 'zztf_laptop_2');
    TicketFieldsService::deleteField($conn, $ctx, $fLaptop);
    is_('a retired field stops appearing', in_array('zztf_laptop', $keys(TicketFieldsService::fieldsFor($conn, $tenant, null)), true), false);
    is_('...and its answers are kept', (int)$conn->query("SELECT COUNT(*) FROM ticket_field_values WHERE field_id = $fLaptop")->fetchColumn(), 1);

    echo "\nThe portal:\n";
    is_('the type picker is offered, because the answer changes what is asked', selfServiceTicketTypeMatters($conn, $tenant), true);
    is_('the types offered include the new ones', isset(selfServiceTicketTypes($conn, $tenant)[$types['starter']]), true);
} catch (Throwable $e) {
    $fail++;
    echo "  FAIL  unexpected: " . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine() . "\n";
} finally {
    if ($ticket) {
        $conn->exec("DELETE FROM ticket_field_values WHERE ticket_id = $ticket");
        $conn->exec("DELETE FROM ticket_audit WHERE ticket_id = $ticket");
        $conn->exec("DELETE FROM tickets WHERE id = $ticket");
    }
    if ($fields) {
        $ids = implode(',', array_map('intval', $fields));
        $conn->exec("DELETE FROM ticket_field_values WHERE field_id IN ($ids)");
        $conn->exec("DELETE FROM ticket_field_types WHERE field_id IN ($ids)");
        $conn->exec("DELETE FROM ticket_field_options WHERE field_id IN ($ids)");
        $conn->exec("DELETE FROM ticket_fields WHERE id IN ($ids)");
    }
    if ($types) $conn->exec("DELETE FROM ticket_types WHERE id IN (" . implode(',', $types) . ")");
    $left = (int)$conn->query("SELECT (SELECT COUNT(*) FROM ticket_fields WHERE label LIKE 'ZZTF %')
                                    + (SELECT COUNT(*) FROM ticket_types WHERE name LIKE 'ZZTF %')
                                    + (SELECT COUNT(*) FROM tickets WHERE ticket_number = 'ZZTF-1')")->fetchColumn();
    is_('cleanup: nothing named ZZTF is left', $left, 0);
}

echo "\n" . str_repeat('-', 78) . "\n  $pass passed, $fail failed\n\n";
exit($fail ? 1 : 0);
