# api/system/get_tenant_email_routing.php

> API endpoint - 161 lines

API: "How email reaches this company" — the derived, read-only routing summary (design §7). Given a company (tenant), work out every way inbound mail reaches it and which identity replies go out from.

Routing is never stored as a per-company "mode" (design §3.5 / §7 rejected
idea) — it's *derived* here from the mailboxes and registered domains:

  - Pinned mailbox (tenant_id = this company) → a dedicated inbound address
    AND the outbound reply identity. Sender domain is ignored.
  - Shared-intake mailbox (tenant_id NULL) → reaches this company only when
    the sender's domain matches one of the company's registered domains.
  - The Default company also catches anything that routed nowhere (triage /
    NULL tenant), so it's never a dead end.

Replies always go back out from the same mailbox the ticket arrived on
(see api/tickets/send_email.php → getMailboxForTicket), so each inbound
path's reply identity is just that path's mailbox.

"Company" is the user-facing word for a tenant; the table/code stays `tenant`.

GET ?tenant_id=N

## The endpoint

| | |
|---|---|
| URL | `api/system/get_tenant_email_routing.php` |
| Protected by | Module access: system (requireModuleAccessJson) |
| Reads | `GET tenant_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [tenants](/code/tables/tenants.md) |  |
| 60 |  | read [tenant_domains](/code/tables/tenant_domains.md) |  |
| 67 |  | read [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |
| 80 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 89 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/mailbox_auth.php](/code/files/includes/mailbox_auth.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [mailboxAuthenticatedSql()](/code/functions/mailboxAuthenticatedSql.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

