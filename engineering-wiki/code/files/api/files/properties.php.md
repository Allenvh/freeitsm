# api/files/properties.php

> API endpoint - 70 lines

GET ?type=item|folder&id= - the Properties window: details, and for a file its versions with their SHA-256 (so someone can prove the copy they hold is the one that was uploaded). Audited as a browse - looking at a file's details is still looking.

## The endpoint

| | |
|---|---|
| URL | `api/files/properties.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |
| Reads | `GET type`, `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 16 |  | read [files_folders](/code/tables/files_folders.md), read [analysts](/code/tables/analysts.md) |  |
| 25 |  | read [files_items](/code/tables/files_items.md) |  |
| 46 |  | read [files_versions](/code/tables/files_versions.md), read [analysts](/code/tables/analysts.md) |  |
| 50 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesAudit()](/code/functions/filesAudit.md), [filesNeed()](/code/functions/filesNeed.md), [filesNeedItem()](/code/functions/filesNeedItem.md)

