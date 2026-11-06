# api/tickets/save_csat_settings.php

> API endpoint - 62 lines

API: Save CSAT settings Validates the four user-facing CSAT keys and writes them to system_settings. The HMAC secret can only be changed via db_verify (intentionally — rotating it would invalidate every outstanding survey link, which should be a deliberate operator action, not a settings-page click).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_csat_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_CSAT (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

