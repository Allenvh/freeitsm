# includes/self_service_mailboxes.php

> PHP - 67 lines

Which mailboxes a self-service user may raise a ticket through (GH #172).

The same rule the analyst side's New Ticket already follows
(api/tickets/get_sendable_mailboxes.php): on a multi-company install, the
mailboxes pinned to the ticket's company plus shared intake (tenant_id IS
NULL, which serves everyone); on a single-company install, every active one.

Before this the portal listed EVERY active mailbox to every user, so a
requester at one client saw - and could choose - another client's support
address, and their ticket was then answered from it.

"The ticket's company" is the user's own, exactly as create_ticket.php
decides it. A user with NO company on a multi-company install raises a ticket
into triage, and sees only shared mailboxes - which covers both of the
requester's suggestions at once ("no company-specific mailboxes", "a general
mailbox if one is configured"), so it needs no setting.

⚠️ One function for the list AND the check. create_ticket.php re-checks the
posted id against this, because a narrowed dropdown has never been a check.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [selfServiceMailboxes()](/code/functions/selfServiceMailboxes.md) | 35 | The active mailboxes a self-service user may raise a ticket through - their own company's and the shared ones. Used for the dropdown and to re-check the posted choice. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 | `selfServiceMailboxes` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 40 | `selfServiceMailboxes` | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Included by:** [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md), [api/self-service/get_mailboxes.php](/code/files/api/self-service/get_mailboxes.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

