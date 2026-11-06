# api/system/managers.php

> API endpoint - 163 lines

API: System -> Managers (discussion #62, step 3).

GET  ?action=settings                       the settings, and whether managers can run
  POST {action:'save', settings:{...}}        save the settings
  GET  ?action=overview&search=&page=N        every manager, a page at a time

Administrators only. The rules themselves live in includes/managers.php; this
endpoint only reads and writes the `managers_*` settings and reports on them.

⚠️ The overview is PAGED and searched on the server. On a large organisation
"every manager" is every line manager in the directory - thousands - and each
row's "can see N people" is several queries. Never "load everyone".

## The endpoint

| | |
|---|---|
| URL | `api/system/managers.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET action`, `GET search`, `GET page` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [managersSettingRules()](/code/functions/managersSettingRules.md) | 27 | Everything a settings value may be. Anything else is refused, not coerced. |
| [managersStatus()](/code/functions/managersStatus.md) | 43 | Can the rules run at all on this install - and if not, why not. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 | `managersStatus` | read [manager_grants](/code/tables/manager_grants.md) |  |
| 69 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 98 |  | read [manager_grants](/code/tables/manager_grants.md) |  |
| 100 |  | read [users](/code/tables/users.md) |  |
| 107 |  | read [users](/code/tables/users.md) |  |
| 113 |  | read [manager_grants](/code/tables/manager_grants.md), read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md) |  |
| 128 |  | read [manager_grants](/code/tables/manager_grants.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/managers.php](/code/files/includes/managers.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [managerGrantMembers()](/code/functions/managerGrantMembers.md), [managerReachSql()](/code/functions/managerReachSql.md), [managersActive()](/code/functions/managersActive.md), [managersResetMemo()](/code/functions/managersResetMemo.md), [managersSettings()](/code/functions/managersSettings.md), [managerTeamPreviewIds()](/code/functions/managerTeamPreviewIds.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md), [ticketViewsReady()](/code/functions/ticketViewsReady.md)

