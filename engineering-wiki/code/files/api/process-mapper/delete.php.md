# api/process-mapper/delete.php

> API endpoint - 38 lines

Delete a process and its steps/connectors

## The endpoint

| | |
|---|---|
| URL | `api/process-mapper/delete.php` |
| Protected by | Module access: process-mapper (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | **delete** [process_connectors](/code/tables/process_connectors.md) |  |
| 29 |  | **delete** [process_steps](/code/tables/process_steps.md) |  |
| 30 |  | **delete** [processes](/code/tables/processes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

