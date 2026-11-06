# api/morning-checks/normalise_status.php

> API endpoint - 32 lines

API: Map a batch of orphan label strings to target StatusIDs. POST body: { mappings: [{ label, statusId }, …] }

Thin UI adapter over MorningChecksService (UI-only).

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/normalise_status.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

