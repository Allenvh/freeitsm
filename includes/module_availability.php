<?php
/**
 * Module availability - switching a whole module off for the entire install (GH #217).
 *
 * Not the same thing as module ACCESS. Access (System -> Modules, issue #30) decides
 * WHO may use a module; availability decides whether the install uses it at all. A
 * switched-off module is gone for everybody, administrators included: no launcher
 * tile, no page, no endpoint, no REST resource, no cross-module button, no workflow
 * trigger or action, no scheduled job. Its data stays exactly where it is, and
 * switching it back on brings everything back as it was.
 *
 * ---------------------------------------------------------------------------
 * WHY ONLY THE SWITCHED-OFF MODULES ARE STORED
 * ---------------------------------------------------------------------------
 * The setting is a list of the modules that are OFF (system_settings
 * 'modules_disabled', a JSON array). A module not on the list is on. So an upgrade,
 * a Database Verification run, a fresh install and a restored backup all mean
 * "every module on", with nothing seeded and nothing for an upgrade to get wrong -
 * the promise that upgrading changes nothing is kept by there being nothing to
 * change. A list of the modules that are ON would have needed every new module
 * adding to every install's row, and would have switched a new module off on any
 * install that missed that.
 *
 * TRAP: it fails OPEN. A setting that cannot be read means every module is on.
 * This is a tidiness switch, not a security control - access grants and the
 * administrator flag are the security controls, and they still apply. Failing
 * closed would make a database hiccup look like half the product vanishing.
 *
 * Tickets and System can never be switched off: Tickets is what the rest hangs
 * from, and switching System off would lock every administrator out of the switch.
 */

/** The modules that cannot be switched off (see the header). */
const MODULES_ALWAYS_ON = ['tickets', 'system'];

/**
 * Features that live INSIDE Tickets and can be switched off on their own (GH #217,
 * slice 2) - key => English name. They share the modules' switched-off list, so
 * moduleIsEnabled('sla') and every helper below work for them unchanged; the keys
 * never collide with a module key.
 *
 * TRAP: a feature switch is AVAILABILITY only. Who may use the feature is still
 * decided by module access and capabilities, exactly as before.
 */
const TICKET_FEATURES = [
    'csat'          => 'Customer satisfaction (CSAT)',
    'sla'           => 'SLA',
    'time-tracking' => 'Time tracking',
    'rota'          => 'Duty rota',
];

/**
 * The modules switched off for the whole install, cached per request.
 *
 * @param bool $fresh  true to read the setting again - after saving it in this same request
 * @return string[] module keys from getModuleRegistry(); [] when none, or when the setting cannot be read
 */
function disabledModules(PDO $conn, bool $fresh = false): array
{
    static $cache = null;
    if ($cache !== null && !$fresh) return $cache;
    $cache = [];
    try {
        $s = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'modules_disabled'");
        $s->execute();
        $list = json_decode((string) $s->fetchColumn(), true);
        if (is_array($list)) {
            $cache = array_values(array_diff(array_filter($list, 'is_string'), MODULES_ALWAYS_ON));
        }
    } catch (Throwable $e) { /* fail open - see the header */ }
    return $cache;
}

/**
 * Is this module switched on for the install? True for any key that is not on the
 * switched-off list, including Tickets, System and keys this file has never heard of.
 *
 * @param string $moduleKey  a module key, e.g. 'tasks'
 */
function moduleIsEnabled(PDO $conn, string $moduleKey): bool
{
    return !in_array($moduleKey, disabledModules($conn), true);
}

/**
 * The refusal every gate gives for a switched-off module: one wording, so the page,
 * the endpoint and the REST API say the same thing.
 *
 * @param string $moduleKey  the switched-off module
 */
function moduleDisabledMessage(string $moduleKey): string
{
    if (isset(TICKET_FEATURES[$moduleKey])) return TICKET_FEATURES[$moduleKey] . ' is switched off for this installation.';
    $name = function_exists('t') ? t("common.modules.$moduleKey.name") : '';
    if ($name === '' || $name === "common.modules.$moduleKey.name") {
        // An endpoint that never loaded the translations: a readable English name.
        $name = ['cmdb' => 'CMDB', 'lms' => 'LMS'][$moduleKey] ?? ucwords(str_replace('-', ' ', $moduleKey));
    }
    return $name . ' is switched off for this installation.';
}

/**
 * For an endpoint with no analyst session - the self-service portal, the device
 * agents, a public test link: refuse with 403 and the switched-off message.
 * Analyst endpoints do not need this; requireModuleAccessJson() already says it.
 *
 * @param string $moduleKey  the module the endpoint serves
 */
function requireModuleOnJson(PDO $conn, string $moduleKey): void
{
    if (moduleIsEnabled($conn, $moduleKey)) return;
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => moduleDisabledMessage($moduleKey), 'module_disabled' => $moduleKey]);
    exit;
}

/**
 * Is this module on? For the portal's pages and nav, which have no connection in
 * hand. Fails open like disabledModules().
 *
 * @param string $moduleKey  a module key
 */
function portalModuleOn(string $moduleKey): bool
{
    try {
        return moduleIsEnabled(connectToDatabase(), $moduleKey);
    } catch (Throwable $e) {
        return true;
    }
}

/**
 * For a scheduled job: true (and one log line) when its module is switched off, so
 * the job can stop before doing anything. Nothing queued is sent later as a backlog -
 * a job that skips simply skips.
 *
 *     if (cronModuleSwitchedOff($conn, 'lms', 'lms_reminders', $trigger)) exit(0);
 *
 * With a trigger the skip is recorded as a JobRun, so System -> Background jobs
 * shows "skipped: switched off" rather than a job that silently stopped.
 *
 * @param string $moduleKey  the module the job serves
 * @param string $job  the job's name, as JobRun knows it
 * @param string|null $trigger  JobRun::scheduledTrigger(), to record the skip; null to only log it
 */
function cronModuleSwitchedOff(PDO $conn, string $moduleKey, string $job, ?string $trigger = null): bool
{
    if (moduleIsEnabled($conn, $moduleKey)) return false;
    $reason = moduleDisabledMessage($moduleKey);
    if ($trigger !== null && class_exists('JobRun')) {
        try { JobRun::skipped($conn, $job, $trigger, $reason); } catch (Throwable $e) { /* the log line still says it */ }
    }
    if (PHP_SAPI === 'cli') echo "[$job] skipped: $reason\n";
    error_log("[$job] skipped: $reason");
    return true;
}
