# api/system/debug-tools/D001_demo_core_import.php

> API endpoint - 556 lines

Debug Tool D001 — Demo Core Data Import

Captures everything we might want to know when a user reports that clicking
"Import Core Data" on the Demo Data screen doesn't work. Designed to be run
once and emailed back — assumes nothing about the surrounding code, so it
works even if the import script, JSON file, config, DB or schema is broken.

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D001_demo_core_import.php` |
| Protected by | Signed-in analyst (session check) |
| Reads | `POST module` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 31 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [bool_str()](/code/functions/bool_str.md) | 40 | Formats a boolean as YES or NO for the report. |
| [safe_size()](/code/functions/safe_size.md) | 47 | Returns a file's size in bytes for the report - 'n/a' when it does not exist, 'unknown' when it cannot be read. |
| [redact_row()](/code/functions/redact_row.md) | 59 | Replaces the personal values in a row (names, email, username, password hash, MFA secret) with "[redacted N chars]", so the report can be shared. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 386 |  | **insert** [analyst_teams](/code/tables/analyst_teams.md) (`analyst_id`, `team_id`) |  |
| 397 |  | **insert** [department_teams](/code/tables/department_teams.md) (`department_id`, `team_id`) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [dbConnectionOptions()](/code/functions/dbConnectionOptions.md)

