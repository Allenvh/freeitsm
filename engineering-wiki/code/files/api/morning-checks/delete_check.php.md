# api/morning-checks/delete_check.php

> API endpoint - 30 lines

API Endpoint: Delete Morning Check. Thin UI adapter over MorningChecksService.

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/delete_check.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson); Permission: Cap::MORNING_CHECKS_CHECKS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

