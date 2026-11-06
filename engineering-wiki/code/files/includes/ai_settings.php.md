# includes/ai_settings.php

> PHP - 288 lines

Per-namespace AI configuration storage, shared by every module that offers an AI provider/model/key panel.

Config for a namespace `<ns>` lives in three system_settings rows:
  <ns>_provider     anthropic | openai | openrouter
  <ns>_model        model id (curated for anthropic/openai; namespaced for openrouter)
  <ns>_api_key      encrypted at rest (key must be in ENCRYPTED_SETTING_KEYS + MASKED_SETTING_KEYS)

SSL certificate verification is NOT configured here — it is a single global
switch (SSL_VERIFY_PEER in config.php, applied via sslApplyCurl). There is no
per-provider toggle: the tick-box that used to live on each AI settings page
was confusing (it ANDed with the global) and gave the false impression the UI
could enable verification the server couldn't actually perform.

SECURITY: the registry is an allowlist. The generic api/system/ai/* endpoints
only ever read/write keys derived from a REGISTERED namespace, so they can't be
abused to read or overwrite arbitrary system_settings (e.g. other secrets).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [aiSettingsRegistry()](/code/functions/aiSettingsRegistry.md) | 30 | Allowlisted AI config namespaces. Adding a module = one line here + dropping renderAiSettingsPanel('<ns>') on its settings page. The <ns>_api_key MUST also be added to ENCRYPTED_SETTING_KEYS + MASKED_SETTING_KEYS in encryption.php. |
| [aiSettingsIsValidNs()](/code/functions/aiSettingsIsValidNs.md) | 106 | True when `$ns` is a namespace registered in `aiSettingsRegistry()`. |
| [aiSettingsEntry()](/code/functions/aiSettingsEntry.md) | 116 | Returns a namespace's registry entry (label, default provider and model); throws RuntimeException for an unknown namespace. |
| [aiSettingsKeys()](/code/functions/aiSettingsKeys.md) | 130 | The system_settings keys for a namespace. |
| [aiSettingsLoad()](/code/functions/aiSettingsLoad.md) | 160 | Load a ready-to-use config (decrypted key) for `aiProviderChat()`. Defaults: provider/model from the registry; verify_ssl from the row, else the global SSL_VERIFY_PEER constant (preserves existing localhost behaviour). |
| [aiSettingsForUi()](/code/functions/aiSettingsForUi.md) | 222 | UI-safe view of the config — never returns the plaintext key, only a mask and a has_key flag. |
| [aiSettingsSave()](/code/functions/aiSettingsSave.md) | 247 | Persist a config. Validates provider; encrypts the key; honours masked/empty "no change" so saving provider+model alone never wipes the key. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 167 | `aiSettingsLoad` | read [system_settings](/code/tables/system_settings.md) |  |
| 260 | `aiSettingsSave` | **update** [system_settings](/code/tables/system_settings.md) (`setting_value`) |  |
| 263 | `aiSettingsSave` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/ai_provider.php](/code/files/includes/ai_provider.php.md)

**Included by:** [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md), [api/forms/_ai_helpers.php](/code/files/api/forms/_ai_helpers.php.md), [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md), [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md), [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md), [api/lms/tests.php](/code/files/api/lms/tests.php.md), [api/messaging/ai_suggest_reply.php](/code/files/api/messaging/ai_suggest_reply.php.md), [api/messaging/ai_summary.php](/code/files/api/messaging/ai_summary.php.md), [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md), [api/problem-management/ai_suggest_problem.php](/code/files/api/problem-management/ai_suggest_problem.php.md), [api/system/ai/get_settings.php](/code/files/api/system/ai/get_settings.php.md), [api/system/ai/save_settings.php](/code/files/api/system/ai/save_settings.php.md), [api/system/ai/test_connection.php](/code/files/api/system/ai/test_connection.php.md), [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md), [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md), [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md), [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md), [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md), [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md), [api/war-room/sitrep.php](/code/files/api/war-room/sitrep.php.md), [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md), [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md), [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md), [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md), [system/ai/index.php](/code/files/system/ai/index.php.md), [system/debug-tools/includes/tools.php](/code/files/system/debug-tools/includes/tools.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [encryptValue()](/code/functions/encryptValue.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [maskSecret()](/code/functions/maskSecret.md)

