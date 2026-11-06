# api/tickets/save_merge_summary.php

> API endpoint - 61 lines

API Endpoint: save the AI merge summary as an internal note.

POST { ticket_id, summary }

Separate from ai_merge_summary.php on purpose. That endpoint streams so the analyst
watches it appear; this one commits it. If the analyst closes the tab mid-stream,
nothing was ever written — a half-finished briefing saved into a ticket would be
worse than none, because the next person would read it as complete.

Always INTERNAL. The summary is a machine's reading of the conversation; it is for
the analyst picking the ticket up, and must never reach the requester. `is_internal`
is hardcoded here rather than taken from the request precisely so that no future
caller can make it shareable by accident.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_merge_summary.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 |  | **insert** [ticket_notes](/code/tables/ticket_notes.md) (`ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

