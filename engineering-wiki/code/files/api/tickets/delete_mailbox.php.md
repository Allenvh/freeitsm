# api/tickets/delete_mailbox.php

> API endpoint - 62 lines

API Endpoint: Delete target mailbox

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_mailbox.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MAILBOXES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 45 |  | **delete** [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

