# api/rfp-builder/get_rfp_departments.php

> API endpoint - 39 lines

API Endpoint: Get RFP departments

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_rfp_departments.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 |  | read [rfp_departments](/code/tables/rfp_departments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

