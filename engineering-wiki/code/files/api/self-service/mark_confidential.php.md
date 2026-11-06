# api/self-service/mark_confidential.php

> API endpoint - 59 lines

Self-service: the requester marks one of their own tickets Confidential. POST JSON { ticket_id }

Discussion #62. One way only: a requester can make their ticket confidential
(kept from their managers) but never make it Normal again. Lowering is a
service-desk decision, because a department or mailbox may have made it
confidential for a reason the requester cannot see - and a requester talked
into "un-confidentialling" a grievance by the manager it is about is exactly
the case this exists to prevent.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/mark_confidential.php` |
| Protected by | Signed-in portal user (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ticketSensitivityRaise()](/code/functions/ticketSensitivityRaise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

