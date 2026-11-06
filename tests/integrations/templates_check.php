<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Every workflow starter recipe must actually be runnable.
 *
 * `workflow/includes/templates.php` promises in its own header that "every
 * trigger_event here is a real, wired trigger … and every action type is a real
 * handler — a recipe that can't actually run would be worse than no recipe at
 * all". Nothing enforced that, and the first tracker recipe shipped with an
 * `add_note` action that does not exist (it is `add_ticket_note`). This does
 * enforce it.
 *
 *   php tests/integrations/templates_check.php
 *
 * ⚠️ SEPARATE FROM run.php ON PURPOSE. `WorkflowEngine::availableActions()`
 * reads webhook formats from the database, and run.php's value is that it needs
 * no database and no network. Do not merge this back into it.
 *
 * Checks, for EVERY template, not just the tracker ones:
 *   1. the trigger exists
 *   2. every action type exists
 *   3. every arg name exists on that action
 *   4. every required arg is supplied (or is a $configure marker the user fills)
 *   5. it resolves against THIS install without throwing
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../workflow/includes/templates.php';

$pass = 0; $fail = 0; $failures = [];
function ok(string $what, bool $cond) {
    global $pass, $fail, $failures;
    if ($cond) { $pass++; return; }
    $fail++; $failures[] = $what;
}

echo "Workflow starter recipes — are they runnable?\n";
echo str_repeat('=', 62) . "\n\n";

$triggers = WorkflowEngine::availableTriggers();
$actions  = WorkflowEngine::availableActions();
$all      = WorkflowTemplates::all();

printf("%d recipes, %d triggers, %d actions available\n\n", count($all), count($triggers), count($actions));

foreach ($all as $key => $tpl) {
    ok("[$key] trigger '{$tpl['trigger_event']}' exists", isset($triggers[$tpl['trigger_event']]));

    foreach (($tpl['actions'] ?? []) as $i => $a) {
        $type = $a['type'] ?? '';
        // ⚠️ NOT `if (!ok(...)) continue;` — ok() returns void, so that is always
        // true and silently skips every check below it. It did exactly that here
        // and the arg validation never ran once.
        ok("[$key] action " . ($i + 1) . " '$type' exists", isset($actions[$type]));
        if (!isset($actions[$type])) continue;

        $spec = $actions[$type]['args'] ?? [];
        foreach (array_keys($a['args'] ?? []) as $argKey) {
            ok("[$key] action '$type' has an arg named '$argKey'", isset($spec[$argKey]));
        }
        // A required arg left out entirely means the recipe clones broken with
        // nothing telling the user which box to fill.
        foreach ($spec as $argKey => $argSpec) {
            if (empty($argSpec['required'])) continue;
            ok("[$key] action '$type' supplies required arg '$argKey'",
               array_key_exists($argKey, $a['args'] ?? []));
        }
    }

    // And it must survive resolution against this install's own lookup tables.
    try {
        $r = WorkflowTemplates::resolve($tpl);
        ok("[$key] resolves without throwing", is_array($r) && isset($r['actions']));
    } catch (Throwable $e) {
        ok("[$key] resolves without throwing — " . $e->getMessage(), false);
    }
}

// ---------------------------------------------------------------------------
// The tracker payload a workflow actually receives
// ---------------------------------------------------------------------------
//
// ⚠️ This exists because the first version of integrationsTicketPayload()
// selected `created_by` and `requester_email` straight off the `tickets` table.
// Neither is a column there — `created_by` is `t.user_id` and `requester_email`
// lives on `users` behind a LEFT JOIN. The query threw, the catch swallowed it,
// and every tracker workflow would have received a payload containing nothing
// but an id. "Tell the requester when dev finishes" would have had nobody to
// tell, silently.

require_once __DIR__ . '/../../includes/integrations/integrations.php';

$conn = connectToDatabase();
if (integrationsSchemaReady($conn)) {
    $link = $conn->query(
        "SELECT * FROM integration_links WHERE entity_type = 'ticket' ORDER BY id DESC LIMIT 1"
    )->fetch(PDO::FETCH_ASSOC);

    if ($link) {
        echo "\nTracker payload (using linked ticket {$link['entity_id']}):\n";
        $p = integrationsTicketPayload($conn, (int)$link['entity_id']);

        // Every key the engine advertises for tracker.* must be present, or a
        // condition/variable referencing it silently resolves to nothing.
        foreach (['id','subject','priority_id','status_id','department_id','type_id',
                  'assigned_analyst_id','created_by','requester_email'] as $k) {
            ok("payload has key '$k'", array_key_exists($k, $p));
        }
        ok('payload id is the right ticket', ($p['id'] ?? null) === (int)$link['entity_id']);
        // Not just present — actually populated, which is what the broken query
        // failed at. A linked ticket came from an email, so it has both.
        ok('payload carries a subject (not the empty fallback)', !empty($p['subject']));
        ok('payload carries requester_email — the "tell the requester" recipe needs it',
           !empty($p['requester_email']));
    } else {
        echo "\n(no ticket links on this install — payload check skipped)\n";
    }
} else {
    echo "\n(integration tables absent — payload check skipped)\n";
}

echo "\n" . str_repeat('=', 62) . "\n";
echo "  passed: $pass\n";
echo "  failed: $fail\n";
if ($failures) {
    echo "\nFailures:\n";
    foreach ($failures as $f) echo "  ✗ $f\n";
}
echo str_repeat('=', 62) . "\n";
exit($fail === 0 ? 0 : 1);
