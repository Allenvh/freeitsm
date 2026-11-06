# api/tickets/save_reply_cleanup_settings.php

> API endpoint - 56 lines

API: Save Reply Cleanup tone + custom instructions.

Provider / model / API key are now owned by the shared AI panel
(api/system/ai/save_settings.php, ns=tickets_reply_cleanup). This endpoint
only persists the reply-cleanup-specific tone and custom-instructions text.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_reply_cleanup_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_REPLY_CLEANUP (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

