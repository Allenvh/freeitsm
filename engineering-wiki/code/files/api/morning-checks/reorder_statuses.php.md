# api/morning-checks/reorder_statuses.php

> API endpoint - 32 lines

API: Reorder morning-check statuses (positions become 10, 20, 30, …). Thin UI adapter over MorningChecksService (UI-only).

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/reorder_statuses.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson); Permission: Cap::MORNING_CHECKS_STATUSES (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

