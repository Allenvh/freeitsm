# api/tickets/save_note.php

> API endpoint - 37 lines

API Endpoint: Save a new note. Thin UI adapter over TicketsService::createNote.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_note.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

