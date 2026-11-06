<?php
/**
 * Workflow — settings manifest. See includes/capabilities.php.
 *
 * NOTE what is deliberately NOT here: authoring workflows. A workflow can send email, call
 * webhooks and change records right across the product, so the ability to write one is
 * governed by module access to Workflow — which is itself the strong permission, and the
 * one to be careful who you grant. These tabs are the module's own configuration.
 *
 * 'formats' are the webhook message shapes (Slack, Teams, Discord, …) — data, not code, so
 * that adding a new destination needs no release.
 */
require_once __DIR__ . '/../../includes/capabilities.php';

return [
    'module' => 'workflow',
    'label'  => 'Workflow',
    'umbrella' => [
        'cap'       => capKey(Cap::WORKFLOW_MANAGE),
        'grant'     => 'Manage everything in Workflow settings',
        'sensitive' => true,   // implies the AI credentials
    ],
    'tabs' => [
        [
            'id'           => 'ai',
            'cap'          => capKey(Cap::WORKFLOW_AI),
            'label_key'    => 'workflow.settings_tabs.ai',
            'grant'        => 'Configure the Workflow AI provider, including its API key',
            'sensitive'    => true,
            'setting_keys' => ['workflow_ai_provider', 'workflow_ai_model', 'workflow_ai_api_key', 'workflow_ai_verify_ssl'],
        ],
        [
            'id'        => 'formats',
            'cap'       => capKey(Cap::WORKFLOW_FORMATS),
            'label_key' => 'workflow.settings_tabs.formats',
            'grant'     => 'Manage the webhook message formats',
        ],
    ],
];
