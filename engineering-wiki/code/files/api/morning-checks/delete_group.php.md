# api/morning-checks/delete_group.php

> API endpoint - 39 lines

API: delete a morning check group (discussion #64).

⚠️ The group goes; its CHECKS DO NOT. They fall back to ungrouped and carry on
appearing in the round. Deleting a way of organising work should never delete
the work, and "delete group" is precisely the button somebody presses
expecting only the grouping to disappear. The response says how many checks
were freed so the UI can tell them.

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/delete_group.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson); Permission: Cap::MORNING_CHECKS_GROUPS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

