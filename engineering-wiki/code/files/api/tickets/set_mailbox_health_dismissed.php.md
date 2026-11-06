# api/tickets/set_mailbox_health_dismissed.php

> API endpoint - 96 lines

API Endpoint: acknowledge (or un-acknowledge) a mailbox health warning.

"Not assigned to a company", "no ticket origin", "no outgoing server" and the
rest can all be somebody's deliberate choice. Without this, a correctly
configured mailbox would carry a warning mark for ever — and a mark that never
clears is one people stop reading, which would cost us the marks that matter.

Only WARNINGS can be dismissed. Errors — reading the wrong inbox, credentials
that no longer decrypt — are faults rather than choices, and are refused here
as well as in the UI, so a hand-made request can't silence one.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/set_mailbox_health_dismissed.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MAILBOXES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 65 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 88 |  | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`health_dismissed`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), [includes/mailbox_health.php](/code/files/includes/mailbox_health.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [mailboxHealthProblems()](/code/functions/mailboxHealthProblems.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

