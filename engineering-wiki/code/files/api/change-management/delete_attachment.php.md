# api/change-management/delete_attachment.php

> API endpoint - 63 lines

API Endpoint: Delete an attachment from a change

## The endpoint

| | |
|---|---|
| URL | `api/change-management/delete_attachment.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [change_attachments](/code/tables/change_attachments.md) |  |
| 47 |  | **delete** [change_attachments](/code/tables/change_attachments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

