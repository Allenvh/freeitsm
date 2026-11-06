# api/tickets/resolve_triage.php

> API endpoint - 182 lines

API: Resolve a triage ticket — file it to a company. POST JSON { ticket_id: int the triage ticket being resolved (required), tenant_id: int target company (0/absent → create a new one), new_company_name: string name for the new company (when tenant_id is 0), domain: string the sender's domain (for mapping/sweeping), map_domain: bool also register `domain` to the company so this and all other queued mail from it route automatically, from_address: string the sender's full address (for sender mapping), map_sender: bool also map `from_address` to the company so this and all other queued mail from that exact address route automatically (the freemail-safe alternative to mapping a whole domain) }

"Mapping a domain sweeps both ways" (design §6): registering the domain both
re-files every queued ticket from it AND auto-routes future mail. Free-email
domains are never mapped (two clients can share them) — for those, map the
specific sender address instead, which sweeps and auto-routes the same way but
only for that one address. Failing both, the ticket is filed on its own.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/resolve_triage.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 65 |  | **insert** [tenants](/code/tables/tenants.md) (`name`, `is_default`, `is_active`, `created_datetime`) |  |
| 90 |  | read [tenant_domains](/code/tables/tenant_domains.md) |  |
| 94 |  | **insert** [tenant_domains](/code/tables/tenant_domains.md) (`tenant_id`, `domain`) |  |
| 106 |  | read [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |
| 110 |  | **insert** [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) (`tenant_id`, `email`) |  |
| 125 |  | **update** [tickets](/code/tables/tickets.md) (`tenant_id`), read [emails](/code/tables/emails.md) |  |
| 140 |  | **update** [tickets](/code/tables/tickets.md) (`tenant_id`) |  |
| 146 |  | **update** [tickets](/code/tables/tickets.md) (`tenant_id`), read [emails](/code/tables/emails.md) |  |
| 160 |  | **update** [tickets](/code/tables/tickets.md) (`tenant_id`) |  |
| 164 |  | **update** [tickets](/code/tables/tickets.md) (`tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getTenantById()](/code/functions/getTenantById.md), [isFreemailDomain()](/code/functions/isFreemailDomain.md), [normaliseEmailAddress()](/code/functions/normaliseEmailAddress.md), [normaliseEmailDomain()](/code/functions/normaliseEmailDomain.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

