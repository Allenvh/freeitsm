# api/tickets/get_mailboxes.php

> API endpoint - 157 lines

API Endpoint: Get all target mailboxes

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_mailboxes.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 31 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 131 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), [includes/mailbox_health.php](/code/files/includes/mailbox_health.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [mailboxAcceptedSet()](/code/functions/mailboxAcceptedSet.md), [mailboxHealthProblems()](/code/functions/mailboxHealthProblems.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

