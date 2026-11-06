<?php
/**
 * Process Mapper — settings manifest. See includes/capabilities.php.
 *
 * Drawing PROCESS MAPS is the everyday job and stays on plain module access. The step
 * types are the vocabulary those maps are drawn with.
 */
require_once __DIR__ . '/../../includes/capabilities.php';

return [
    'module' => 'process-mapper',
    'label'  => 'Process mapper',
    'umbrella' => [
        'cap'   => capKey(Cap::PROCESS_MAPPER_MANAGE),
        'grant' => 'Manage everything in Process mapper settings',
    ],
    'tabs' => [
        [
            'id'        => 'step-types',
            'cap'       => capKey(Cap::PROCESS_MAPPER_STEP_TYPES),
            'label_key' => 'process-mapper.settings_tabs.step_types',
            'grant'     => 'Manage the step types maps are drawn with',
        ],
        [
            'id'        => 'left-panel',
            'cap'       => null,
            'label_key' => 'process-mapper.settings_tabs.left_panel',
        ],
    ],
];
