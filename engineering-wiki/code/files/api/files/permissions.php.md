# api/files/permissions.php

> API endpoint - 119 lines

The Permissions window, and taking ownership.

GET  ?folder=<id>                      View: who has what here - the folder's
       own entries and (while inheriting) every ancestor's, marked inherited.
  POST {action: save, folder, entries: [{principal_type, principal_id, level}]}
       Full control. REPLACES the folder's own entries; inherited ones are
       changed on the folder they come from, as in Windows. Each change is a
       line in the audit row (added / changed / removed).
  POST {action: take_ownership, folder}
       Cap::FILES_FOLDERS - the break-glass. Works on a folder the caller
       CANNOT see (that is the point: an orphaned folder, its owner gone), and
       gives them Full control on it. Loudly audited.

## The endpoint

| | |
|---|---|
| URL | `api/files/permissions.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |
| Reads | `GET folder` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | **insert** [files_permissions](/code/tables/files_permissions.md) (`folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime`) |  |
| 69 |  | read [teams](/code/tables/teams.md) |  |
| 69 |  | read [analysts](/code/tables/analysts.md) |  |
| 74 |  | read [files_permissions](/code/tables/files_permissions.md) |  |
| 81 |  | **delete** [files_permissions](/code/tables/files_permissions.md) |  |
| 82 |  | **insert** [files_permissions](/code/tables/files_permissions.md) (`folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime`) |  |
| 107 |  | read [teams](/code/tables/teams.md) |  |
| 107 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiBody()](/code/functions/filesApiBody.md), [filesApiFail()](/code/functions/filesApiFail.md), [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesAudit()](/code/functions/filesAudit.md), [filesHasCap()](/code/functions/filesHasCap.md), [filesNeed()](/code/functions/filesNeed.md)

