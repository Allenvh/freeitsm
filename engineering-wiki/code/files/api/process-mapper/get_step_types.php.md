# api/process-mapper/get_step_types.php

> API endpoint - 35 lines

API Endpoint: Get process step types (the configurable block-type palette).

## The endpoint

| | |
|---|---|
| URL | `api/process-mapper/get_step_types.php` |
| Protected by | Module access: process-mapper (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 19 |  | read [process_step_types](/code/tables/process_step_types.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

