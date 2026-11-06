# api/forms/_ai_helpers.php

> API endpoint - 71 lines

Forms AI helpers — Stage 2 of the per-module AI billing model.

The form builder's AI Assist (api/forms/ai_generate.php) used to share
the RFP Builder's Anthropic key from `rfp_ai_*` settings. To let
admins bill the Forms feature against its own key/workspace, this file
resolves config from `forms_ai_*` entries in `system_settings`. Same
pattern as Workflow's `_ai_helpers.php` (#343).

Public surface:
  loadFormsAiConfig(PDO)            -> ['provider', 'model', 'api_key', 'verify_ssl']
  formsEffectiveSslVerify(`$perCall`) -> bool (combine per-form toggle + global kill switch)

Default model + suggestion lists also exposed as constants so the
settings page and the test endpoint stay in sync.

## The endpoint

| | |
|---|---|
| URL | `api/forms/_ai_helpers.php` |
| Protected by | Helper - included by other endpoints, not called directly |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [formsEffectiveSslVerify()](/code/functions/formsEffectiveSslVerify.md) | 48 | Apply the global SSL_VERIFY_PEER kill switch on top of the per-form toggle. Same behaviour as the equivalent helper on Workflow. |
| [loadFormsAiConfig()](/code/functions/loadFormsAiConfig.md) | 61 | Load `forms_ai_*` settings. Throws if no key is saved so callers can surface a clear "configure AI under Forms → Settings → AI" message. |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Included by:** [api/forms/ai_generate.php](/code/files/api/forms/ai_generate.php.md), [api/forms/test_ai_key.php](/code/files/api/forms/test_ai_key.php.md)

**Calls:** [aiSettingsLoad()](/code/functions/aiSettingsLoad.md)

