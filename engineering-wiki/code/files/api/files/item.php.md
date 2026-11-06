# api/files/item.php

> API endpoint - 41 lines

POST {action, id, ...} - change a file.

rename  {id, name}   Modify on its folder
  delete  {id}         Modify. Soft: to the recycle bin; the bytes stay on disk.

## The endpoint

| | |
|---|---|
| URL | `api/files/item.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 |  | **update** [files_items](/code/tables/files_items.md) (`name`, `updated_by`, `updated_datetime`) |  |
| 32 |  | **update** [files_items](/code/tables/files_items.md) (`deleted_by`, `deleted_datetime`) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiBody()](/code/functions/filesApiBody.md), [filesApiFail()](/code/functions/filesApiFail.md), [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesAudit()](/code/functions/filesAudit.md), [filesCleanName()](/code/functions/filesCleanName.md), [filesNameTaken()](/code/functions/filesNameTaken.md), [filesNeedItem()](/code/functions/filesNeedItem.md)

