# api/files/recent.php

> API endpoint - 32 lines

GET - the start menu's Recent list: files this person last uploaded, downloaded or viewed, newest first, read back out of the audit trail - and filtered through the CURRENT permissions, so a file they have since lost access to (or that was deleted) drops off rather than leaking its name.

## The endpoint

| | |
|---|---|
| URL | `api/files/recent.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 11 |  | read [files_audit](/code/tables/files_audit.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesItem()](/code/functions/filesItem.md)

