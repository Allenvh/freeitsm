<?php
/**
 * Reporting — permissions manifest.
 *
 * Reporting has no settings page, so this manifest declares no tabs - only the two
 * everyday-work permissions for background job health (#182). They live here, not
 * in System, because the Background jobs tab is in Reporting -> System logs, beside
 * the logs it belongs with. See includes/job_health.php.
 *
 * Both ALWAYS apply ('restrict' => null): administrators hold them, everyone else is
 * given them through System -> Roles. A permission that started unrestricted would
 * show every analyst with Reporting the names of the install's directories, servers
 * and tracker connections - which is why the issue agreed a separate permission.
 */

require_once __DIR__ . '/../../includes/capabilities.php';

return [
    'module' => 'reporting',
    'label'  => 'Reporting',

    'tabs' => [],

    'actions' => [
        [
            'cap'      => capKey(Cap::REPORTING_JOB_HEALTH),
            'grant'    => 'View background job health (Reporting -> System logs -> Background jobs, and the Watchtower card)',
            'restrict' => null,
        ],
        [
            'cap'       => capKey(Cap::REPORTING_JOB_SETTINGS),
            'grant'     => 'Change what background jobs are expected to do - their intervals, and marking one "not used here"',
            'sensitive' => true,
            'restrict'  => null,
        ],
    ],
];
