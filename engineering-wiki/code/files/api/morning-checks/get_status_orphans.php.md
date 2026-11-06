# api/morning-checks/get_status_orphans.php

> API endpoint - 59 lines

API: Get orphan status labels — distinct Status string values from morningChecks_Results where StatusID IS NULL, with row counts.

Drives:
  - The dashboard's "you have N unmapped results" warning banner
  - The Settings → Statuses → Normalisation tool listing

Returns:
  { success, totalOrphans, labels: [{label, count}, ...] }

Rows where both StatusID and Status are NULL (a check the analyst
never picked a status for) are NOT orphans — those just haven't
been answered yet. The filter requires Status IS NOT NULL.

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/get_status_orphans.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

