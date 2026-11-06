# api/tickets/get_sendable_mailboxes.php

> API endpoint - 80 lines

API Endpoint: list mailboxes an analyst can SEND a reply FROM for the company they're currently working in. Used to populate the "Send replies from" picker on the New Ticket modal so a manually-created ticket is paired with a mailbox.

"Relevant" = active + authenticated mailboxes that are either pinned to the
active company OR shared intake (tenant_id IS NULL, which serves everyone).
At N=1 there's no company concept, so it's simply all active+authenticated
mailboxes. (Kept separate from get_mailboxes.php, which the inbound poll uses
and which must return every mailbox.)

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_sendable_mailboxes.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [tenants](/code/tables/tenants.md) |  |
| 44 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 52 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

