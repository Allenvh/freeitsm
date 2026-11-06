# api/change-management/get_attachment.php

> API endpoint - 81 lines

API Endpoint: Serve/download an attachment file

## The endpoint

| | |
|---|---|
| URL | `api/change-management/get_attachment.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [change_attachments](/code/tables/change_attachments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

