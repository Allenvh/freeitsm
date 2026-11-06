# api/morning-checks/save_check_result.php

> API endpoint - 33 lines

API Endpoint: Save Morning Check Result. Thin UI adapter over MorningChecksService (upsert: one result per check per date).

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/save_check_result.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

