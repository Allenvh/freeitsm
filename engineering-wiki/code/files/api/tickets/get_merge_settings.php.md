# api/tickets/get_merge_settings.php

> API endpoint - 31 lines

API Endpoint: the install's merge policy, for the merge dialog.

GET -> { reference_mode, originals_mode, ai_summary }

A READ of policy, not of credentials, so it stops at module access — every analyst
who can merge needs to know what merging will do before they press the button. The
capability (Cap::TICKETS_MERGE) guards CHANGING the policy, on the settings tab.

Exists as its own endpoint rather than reusing get_system_settings.php so the merge
dialog does not depend on a settings-level read that an ordinary analyst may not be
entitled to.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_merge_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [mergeSettings()](/code/functions/mergeSettings.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

