# api/morning-checks/get_todays_checks.php

> API endpoint - 122 lines

API Endpoint: Get Today's Morning Checks with Results

Normalised: JOINs morningChecks_Results to morningChecks_Statuses
so the response carries the StatusID + Label + Colour + RequiresNotes
already. Orphan rows (StatusID NULL but Status string set, e.g. from
a since-deleted status) come back with StatusID = null and the
orphan label string in Status — the dashboard surfaces those in a
warning banner and offers a normalisation tool in Settings.

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/get_todays_checks.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson) |
| Reads | `GET date` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

