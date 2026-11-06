<?php
/**
 * Watchtower — settings manifest.
 *
 * Watchtower is the one module that owns no data: it reads every other module
 * and shows what needs attention. So its settings are entirely about the VIEW,
 * and neither capability here grants any access to the things being counted —
 * each card still shows only what the analyst is allowed to see.
 *
 * Why this screen exists at all, given the statuses are configured elsewhere:
 * "which statuses appear on my dashboard" is a fact about the dashboard, not
 * about the status. Statuses carry their own facts — is_closed, is_default —
 * which the SLA engine and half the application depend on, and mixing one
 * view's preferences into them would mean tuning Watchtower by touring three
 * other modules' settings screens.
 *
 * The two tabs are split because they answer different questions and carry
 * different risk. CARDS is layout: hide the Contracts card and you have tidied
 * a screen. COUNTS changes what a number MEANS — narrow "high priority" and the
 * figure everyone reads each morning silently changes definition — so it is
 * worth being able to grant the first without the second.
 */

require_once __DIR__ . '/../../includes/capabilities.php';

return [
    'module' => 'watchtower',
    'label'  => 'Watchtower',

    'umbrella' => [
        'cap'   => capKey(Cap::WATCHTOWER_MANAGE),
        'grant' => 'Manage everything in Watchtower settings',
    ],

    'tabs' => [
        [
            'id'        => 'cards',
            'cap'       => capKey(Cap::WATCHTOWER_CARDS),
            'label_key' => 'watchtower.settings.tab_cards',
            'grant'     => 'Choose which cards appear on Watchtower',
        ],
        [
            'id'        => 'counts',
            'cap'       => capKey(Cap::WATCHTOWER_COUNTS),
            'label_key' => 'watchtower.settings.tab_counts',
            'grant'     => 'Choose which statuses and priorities each Watchtower count includes',
        ],
    ],
];
