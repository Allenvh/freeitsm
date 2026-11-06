<?php
/**
 * Problem Management — settings manifest. See includes/capabilities.php.
 *
 * The AI tab uses the shared AI settings panel (namespace problem_ai), so its provider and
 * API key are authorised by namespace against the setting keys declared below.
 *
 * Investigating problems — raising them, adding notes, running the root-cause analysis —
 * is the everyday job and stays on plain module access.
 */
require_once __DIR__ . '/../../includes/capabilities.php';

return [
    'module' => 'problems',
    'label'  => 'Problem management',
    'umbrella' => [
        'cap'       => capKey(Cap::PROBLEMS_MANAGE),
        'grant'     => 'Manage everything in Problem management settings',
        'sensitive' => true,   // implies the AI credentials
    ],
    'tabs' => [
        [
            'id'        => 'statuses',
            'cap'       => capKey(Cap::PROBLEMS_STATUSES),
            'label'     => 'Statuses',
            'grant'     => 'Manage problem statuses',
        ],
        [
            'id'        => 'priorities',
            'cap'       => capKey(Cap::PROBLEMS_PRIORITIES),
            'label'     => 'Priorities',
            'grant'     => 'Manage problem priorities',
        ],
        [
            'id'           => 'ai',
            'cap'          => capKey(Cap::PROBLEMS_AI),
            'label'        => 'Problem AI',
            'grant'        => 'Configure the Problem AI provider, including its API key',
            'sensitive'    => true,
            'setting_keys' => ['problem_ai_provider', 'problem_ai_model', 'problem_ai_api_key', 'problem_ai_verify_ssl'],
        ],
    ],
];
