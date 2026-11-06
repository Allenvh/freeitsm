# api/morning-checks/save_group.php

> API endpoint - 34 lines

API: create or update a morning check group (discussion #64).

⚠️ A group's assignment is routing, not permission. Saving one here changes
who a check is LABELLED for and how the dashboard filters — it does not and
must not change who may complete it. See MorningChecksService.

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/save_group.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson); Permission: Cap::MORNING_CHECKS_GROUPS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

