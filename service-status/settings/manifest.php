<?php
/**
 * Service Status — settings manifest. See includes/capabilities.php.
 *
 * Raising and updating an INCIDENT is the everyday job and stays on plain module access.
 * These tabs define the services whose status is tracked, and the vocabulary incidents are
 * described with.
 */
require_once __DIR__ . '/../../includes/capabilities.php';

return [
    'module' => 'service-status',
    'label'  => 'Service status',
    'umbrella' => [
        'cap'   => capKey(Cap::SERVICE_STATUS_MANAGE),
        'grant' => 'Manage everything in Service status settings',
    ],
    'tabs' => [
        [
            'id'        => 'services',
            'cap'       => capKey(Cap::SERVICE_STATUS_SERVICES),
            'label_key' => 'service-status.settings.tab_services',
            'grant'     => 'Manage the services whose status is tracked',
        ],
        [
            'id'        => 'statuses',
            'cap'       => capKey(Cap::SERVICE_STATUS_STATUSES),
            'label_key' => 'service-status.settings.tab_statuses',
            'grant'     => 'Manage incident statuses',
        ],
        [
            'id'        => 'impacts',
            'cap'       => capKey(Cap::SERVICE_STATUS_IMPACTS),
            'label_key' => 'service-status.settings.tab_impacts',
            'grant'     => 'Manage impact levels',
        ],
        [
            // Uptime (discussion #59). Deliberately NOT a 'downtime rules' tab:
            // whether a given level counts as downtime is a property of the level
            // and lives on the Impact levels tab, so a custom level added later is
            // asked the question at creation rather than needing a second list to
            // be remembered. This tab holds only the two things that are nobody
            // else's property — the window, and whether customers see it.
            'id'           => 'uptime',   // see includes/services/service_uptime.php
            'cap'          => capKey(Cap::SERVICE_STATUS_UPTIME),
            'label_key'    => 'service-status.settings.tab_uptime',
            'grant'        => 'Configure uptime reporting and whether customers see it',
            'setting_keys' => ['status_uptime_window_days', 'status_uptime_show_portal'],
        ],
    ],
];
