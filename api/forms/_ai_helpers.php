<?php
/**
 * Forms AI helpers — Stage 2 of the per-module AI billing model.
 *
 * The form builder's AI Assist (api/forms/ai_generate.php) used to share
 * the RFP Builder's Anthropic key from `rfp_ai_*` settings. To let
 * admins bill the Forms feature against its own key/workspace, this file
 * resolves config from `forms_ai_*` entries in `system_settings`. Same
 * pattern as Workflow's `_ai_helpers.php` (#343).
 *
 * Public surface:
 *   loadFormsAiConfig(PDO)            -> ['provider', 'model', 'api_key', 'verify_ssl']
 *   formsEffectiveSslVerify($perCall) -> bool (combine per-form toggle + global kill switch)
 *
 * Default model + suggestion lists also exposed as constants so the
 * settings page and the test endpoint stay in sync.
 */

require_once __DIR__ . '/../../includes/encryption.php';
require_once __DIR__ . '/../../includes/ai_settings.php';

const FORMS_AI_VALID_PROVIDERS = ['anthropic', 'openai'];

const FORMS_AI_DEFAULT_MODEL = [
    'anthropic' => 'claude-sonnet-4-6',
    'openai'    => 'gpt-4o',
];

const FORMS_AI_MODEL_OPTIONS = [
    'anthropic' => [
        ['id' => 'claude-opus-4-7',           'label' => 'Opus 4.7 — most capable'],
        ['id' => 'claude-sonnet-4-6',         'label' => 'Sonnet 4.6 — recommended (best balance)'],
        ['id' => 'claude-haiku-4-5-20251001', 'label' => 'Haiku 4.5 — fastest and cheapest'],
    ],
    'openai' => [
        ['id' => 'gpt-4.1',     'label' => 'GPT-4.1 — most capable'],
        ['id' => 'gpt-4o',      'label' => 'GPT-4o — recommended default'],
        ['id' => 'gpt-4o-mini', 'label' => 'GPT-4o mini — fastest and cheapest'],
    ],
];

/**
 * Apply the global SSL_VERIFY_PEER kill switch on top of the per-form
 * toggle. Same behaviour as the equivalent helper on Workflow.
 *
 * @param bool $perCallVerify  the form's own "verify SSL" setting; the global switch can only turn it off
 */
function formsEffectiveSslVerify(bool $perCallVerify): bool
{
    $global = defined('SSL_VERIFY_PEER') ? (bool)SSL_VERIFY_PEER : true;
    return $global && $perCallVerify;
}

/**
 * Load `forms_ai_*` settings. Throws if no key is saved so callers can
 * surface a clear "configure AI under Forms → Settings → AI" message.
 *
 * Returns a dict with the four fields the streaming helper expects:
 * provider, model, api_key (decrypted), verify_ssl.
 */
function loadFormsAiConfig(PDO $conn): array
{
    // Provider / model / key / verify_ssl now come from the shared building
    // block (ns=forms_ai), which adds OpenRouter alongside Anthropic/OpenAI.
    $cfg = aiSettingsLoad($conn, 'forms_ai');
    if (!aiConfigured($cfg)) {
        throw new Exception('Forms AI is not configured. Set your provider, model and API key under Forms → Settings → AI.');
    }
    return $cfg;
}
