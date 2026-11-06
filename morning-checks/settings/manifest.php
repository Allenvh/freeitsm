<?php
/**
 * Morning Checks — settings manifest. See includes/capabilities.php.
 *
 * The 'chart' tab is NOT administration: it stores a per-analyst display preference (the
 * bar fill style) via api/system/set_user_preference.php, exactly like the left-panel tab
 * on other modules. So it declares no capability and everyone with the module sees it.
 *
 * Recording a check RESULT is the everyday job and stays on plain module access.
 */
require_once __DIR__ . '/../../includes/capabilities.php';

return [
    'module' => 'morning-checks',
    'label'  => 'Morning checks',
    'umbrella' => [
        'cap'   => capKey(Cap::MORNING_CHECKS_MANAGE),
        'grant' => 'Manage everything in Morning checks settings',
    ],
    'tabs' => [
        [
            'id'        => 'checks',
            'cap'       => capKey(Cap::MORNING_CHECKS_CHECKS),
            'label_key' => 'morning-checks.settings.tab_checks',
            'grant'     => 'Manage the checks themselves, and their order',
        ],
        [
            'id'        => 'statuses',
            'cap'       => capKey(Cap::MORNING_CHECKS_STATUSES),
            'label_key' => 'morning-checks.settings.tab_statuses',
            'grant'     => 'Manage check statuses',
        ],
        [
            // Optional grouping, and who the round is pointed at (discussion #64).
            // ⚠️ Granting this does NOT grant the right to complete a check, and
            // withholding it does not withhold that either — completing a check
            // is plain module access, so the round still gets done when the
            // person it is routed to is away.
            'id'        => 'groups',
            'cap'       => capKey(Cap::MORNING_CHECKS_GROUPS),
            'label_key' => 'morning-checks.settings.tab_groups',
            'grant'     => 'Group checks and route them to a team or an analyst',
        ],
        [
            // A per-analyst display preference, not administration.
            'id'        => 'chart',
            'cap'       => null,
            'label_key' => 'morning-checks.settings.tab_chart',
        ],
    ],
];
