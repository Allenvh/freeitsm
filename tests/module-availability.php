<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/** Module availability (GH #217) - switching a whole module off for the install. No database, no network.
 *
 * 1. Every REST API v1 route's permission resource maps to a module (api/v1/lib/modules.php), so a new
 *    resource cannot quietly keep answering while its module is off.
 * 2. Every workflow trigger's first segment maps to a module or is one of Tickets' own.
 * 3. Every module named in those maps, and on the Watchtower cards, is a real module key.
 * 4. The setting reader: no row = everything on, a list switches those off, Tickets and System can
 *    never be off, a broken value fails open. Each case runs in its own process, because
 *    disabledModules() caches per request exactly as it should.
 * Control: drop a resource from API_V1_RESOURCE_MODULES, or a segment from TRIGGER_MODULES, and 1/2 fail.
 */

// ---- One reader case, in a fresh process: php module-availability.php --case <base64 json, or none> ----
if (($argv[1] ?? '') === '--case') {
    require __DIR__ . '/../includes/module_availability.php';
    $pdo = new PDO('sqlite::memory:');
    $pdo->exec('CREATE TABLE system_settings (setting_key TEXT PRIMARY KEY, setting_value TEXT)');
    if ($argv[2] !== 'none') {
        $pdo->prepare("INSERT INTO system_settings VALUES ('modules_disabled', ?)")->execute([base64_decode($argv[2])]);
    }
    echo json_encode([
        'disabled' => disabledModules($pdo),
        'tasks'    => moduleIsEnabled($pdo, 'tasks'),
        'tickets'  => moduleIsEnabled($pdo, 'tickets'),
        'system'   => moduleIsEnabled($pdo, 'system'),
    ]);
    exit(0);
}

require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../api/v1/lib/modules.php';
require __DIR__ . '/../workflow/includes/engine.php';
require __DIR__ . '/../includes/watchtower_settings.php';

$failed = 0;
$ok = function (string $label, bool $pass, string $detail = '') use (&$failed) {
    if (!$pass) $failed++;
    echo ($pass ? '  PASS  ' : '  FAIL  ') . $label . ($detail !== '' ? "  [$detail]" : '') . "\n";
};
$modules = array_merge(array_keys(getModuleRegistry()), ['system'], array_keys(TICKET_FEATURES));   // features share the list (slice 2)

// 1. REST resources
$routes = require __DIR__ . '/../api/v1/lib/routes.php';
$unmapped = [];
foreach ($routes as [, , $perm]) {
    if ($perm !== null && $perm[0] !== 'reference' && !isset(API_V1_RESOURCE_MODULES[$perm[0]])) $unmapped[] = $perm[0];
}
$ok('every REST API resource belongs to a module', !$unmapped, implode(', ', array_unique($unmapped)));
$bad = [];
foreach (array_merge(array_merge(...array_values(API_V1_RESOURCE_MODULES)), array_merge(...array_values(API_V1_REFERENCE_PATH_MODULES))) as $m) {
    if (!in_array($m, $modules, true)) $bad[] = $m;
}
$ok('the REST map names only real modules', !$bad, implode(', ', array_unique($bad)));
$ok('a project\'s tasks need Tasks as well', apiV1ModulesFor('projects', '/projects/4/tasks') === ['projects', 'tasks']);
$ok('a task lookup needs Tasks', apiV1ModulesFor('reference', '/task-statuses') === ['tasks']);
$ok('a ticket lookup needs nothing switchable', apiV1ModulesFor('reference', '/statuses') === []);
$ok('a ticket\'s SLA needs the SLA feature', apiV1ModulesFor('ticket_sla', '/tickets/4/sla') === ['tickets', 'sla']);
$ok('a ticket\'s time entries need Time tracking', apiV1ModulesFor('ticket_time_entries', '/tickets/4/time-entries') === ['tickets', 'time-tracking']);
$ok('no Tickets feature shares a key with a module', !array_intersect(array_keys(TICKET_FEATURES), array_keys(getModuleRegistry())));

// 2. Workflow triggers
$unmapped = [];
foreach (array_keys(WorkflowEngine::availableTriggers()) as $trigger) {
    $seg = explode('.', $trigger, 2)[0];
    if (!isset(WorkflowEngine::TRIGGER_MODULES[$seg]) && !in_array($seg, WorkflowEngine::TICKET_TRIGGER_SEGMENTS, true)) $unmapped[] = $trigger;
}
$ok('every workflow trigger belongs to a module', !$unmapped, implode(', ', $unmapped));
$bad = array_diff(array_merge(array_values(WorkflowEngine::TRIGGER_MODULES), array_values(WorkflowEngine::ACTION_MODULES)), $modules);
$ok('the workflow maps name only real modules', !$bad, implode(', ', $bad));

// 3. Watchtower cards
$bad = array_diff(array_column(wtCardRegistry(), 'module'), $modules);
$ok('every Watchtower card names a real module', !$bad, implode(', ', $bad));
$ok('Tickets and System are the modules that are always on', MODULES_ALWAYS_ON === ['tickets', 'system']);

// 4. The reader, one process per case
$case = function (string $value) {
    $out = shell_exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__FILE__) . ' --case ' . ($value === 'none' ? 'none' : base64_encode($value)));   // base64: Windows' escapeshellarg() drops double quotes
    return json_decode((string)$out, true) ?: [];
};
$r = $case('none');
$ok('no setting at all: every module on (an upgrade changes nothing)', ($r['disabled'] ?? null) === [] && $r['tasks'] === true);
$r = $case('["tasks","lms"]');
$ok('a saved list switches those modules off', ($r['disabled'] ?? null) === ['tasks', 'lms'] && $r['tasks'] === false);
$r = $case('["tickets","system","tasks"]');
$ok('Tickets and System stay on even if the list names them', ($r['disabled'] ?? null) === ['tasks'] && $r['tickets'] && $r['system']);
$r = $case('not json');
$ok('a broken value fails open: every module on', ($r['disabled'] ?? null) === [] && $r['tasks'] === true);

echo $failed ? "$failed failed\n" : "all passed\n";
exit($failed ? 1 : 0);
