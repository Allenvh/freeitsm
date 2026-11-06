# api/cmdb/save_ai_settings.php

> API endpoint - 44 lines

API: Save CMDB AI custom instructions.

Provider / model / API key are now owned by the shared AI panel
(api/system/ai/save_settings.php, ns=cmdb_ai). This endpoint only persists
the CMDB-specific custom-instructions text appended to the system prompt.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/save_ai_settings.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_AI (requireCapabilityJson) |
| Called from | [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

