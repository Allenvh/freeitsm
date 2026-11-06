<?php
/**
 * Calendar — settings manifest. See includes/capabilities.php.
 *
 * Creating and editing EVENTS is the everyday job and stays on plain module access.
 * Categories are the vocabulary events are filed under.
 */
require_once __DIR__ . '/../../includes/capabilities.php';

return [
    'module' => 'calendar',
    'label'  => 'Calendar',
    'umbrella' => [
        'cap'   => capKey(Cap::CALENDAR_MANAGE),
        'grant' => 'Manage everything in Calendar settings',
    ],
    'tabs' => [
        [
            'id'        => 'categories',
            'cap'       => capKey(Cap::CALENDAR_CATEGORIES),
            'label_key' => 'calendar.settings.tab_categories',
            'grant'     => 'Manage event categories',
        ],
        [
            'id'        => 'left-panel',
            'cap'       => null,
            'label_key' => 'common.left_panel.tab',
        ],
    ],
];
