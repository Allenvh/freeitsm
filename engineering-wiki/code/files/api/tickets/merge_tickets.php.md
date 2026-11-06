# api/tickets/merge_tickets.php

> API endpoint - 53 lines

API Endpoint: merge tickets.

POST { source_ids: [1,2], target_id: 3 }
  -> { success, target_id, target_number, merged: [...], created_new: bool, settings }

Guarded by module access and nothing more. Merging is everyday service-desk work —
the same category as closing a ticket or reassigning it — so it is not behind a
capability. What IS behind Cap::TICKETS_MERGE is the POLICY (Tickets → Settings →
Merge behaviour): whether a merge keeps the requester's reference alive, and what
happens to the conversations. Deciding that for the whole install is an
administrator's job; doing a merge is the job.

The engine itself lives in includes/ticket_merge.php, where the reasoning is
written up. This file is a thin adapter: validate, call, report.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/merge_tickets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [mergeTickets()](/code/functions/mergeTickets.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

