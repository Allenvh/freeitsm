# api/tickets/get_ticket_priorities.php

> API endpoint - 42 lines

API Endpoint: Get ticket priorities

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_priorities.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

