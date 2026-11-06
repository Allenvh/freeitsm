# api/tickets/get_departments.php

> API endpoint - 46 lines

API Endpoint: Get departments

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_departments.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [departments](/code/tables/departments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

