# api/process-mapper/list.php

> API endpoint - 24 lines

List all processes

## The endpoint

| | |
|---|---|
| URL | `api/process-mapper/list.php` |
| Protected by | Module access: process-mapper (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 18 |  | read [processes](/code/tables/processes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

