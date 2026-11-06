# api/process-mapper/get.php

> API endpoint - 77 lines

Get a single process with its steps and connectors

## The endpoint

| | |
|---|---|
| URL | `api/process-mapper/get.php` |
| Protected by | Module access: process-mapper (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [processes](/code/tables/processes.md) |  |
| 34 |  | read [process_steps](/code/tables/process_steps.md) |  |
| 38 |  | read [process_connectors](/code/tables/process_connectors.md) |  |
| 46 |  | read [process_groups](/code/tables/process_groups.md) |  |
| 53 |  | read [process_lanes](/code/tables/process_lanes.md) |  |
| 62 |  | read [process_annotations](/code/tables/process_annotations.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

