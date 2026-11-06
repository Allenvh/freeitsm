# api/files/list.php

> API endpoint - 134 lines

GET ?folder=<id> - what is inside a folder, for an Explorer window. GET ?folder=0 - "Documents": the top of what this person can see. &tree=1 - subfolders only, for expanding the left-hand tree. Not audited: it shows names the person is already allowed to see, and the tree expands on hover-and-click far too often for a row each time to mean anything. Opening a folder IS.

"The top" is not the real top level: it is every folder you can see whose
parent you cannot. Someone granted only Finance/Invoices sees Invoices at the
top, with its full path as a hint, and never learns what else is in Finance.

## The endpoint

| | |
|---|---|
| URL | `api/files/list.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |
| Reads | `GET folder`, `GET tree` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [files_folders](/code/tables/files_folders.md) |  |
| 62 |  | read [files_versions](/code/tables/files_versions.md), read [files_items](/code/tables/files_items.md), read [analysts](/code/tables/analysts.md) |  |
| 89 |  | read [files_shortcuts](/code/tables/files_shortcuts.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiFail()](/code/functions/filesApiFail.md), [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesAudit()](/code/functions/filesAudit.md), [filesHasCap()](/code/functions/filesHasCap.md), [filesShortcutTarget()](/code/functions/filesShortcutTarget.md)

