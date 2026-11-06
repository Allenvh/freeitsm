# api/morning-checks/get_groups.php

> API endpoint - 29 lines

API: list morning check groups (discussion #64).

Read-only and ungated beyond module access: the dashboard needs group names
and routing to draw its headings, and every analyst sees the dashboard.
Editing them is what needs the capability — see save_group.php.

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/get_groups.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson) |
| Reads | `GET active_only` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

