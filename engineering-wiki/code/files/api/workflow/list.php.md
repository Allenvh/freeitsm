# api/workflow/list.php

> API endpoint - 30 lines

API: List workflows for the landing page.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/list.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 19 |  | read [workflows](/code/tables/workflows.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

