# api/morning-checks/save_status.php

> API endpoint - 39 lines

API: Save (insert or update) a morning-check status. POST body: { statusId?, label, colour, requiresNotes, isActive, sortOrder? }

Thin UI adapter over MorningChecksService (UI-only — the REST API only lists statuses).

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/save_status.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson); Permission: Cap::MORNING_CHECKS_STATUSES (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

