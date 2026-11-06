# api/system/add_tenant_sender.php

> API endpoint - 79 lines

API: Map a specific sender address to a company (tenant) for shared-intake routing. POST JSON { tenant_id, email }

The address-level twin of add_tenant_domain.php. Unlike domains, freemail
addresses ARE allowed here — that's the whole point: route an individual
personal address (jane@gmail.com) to a company even though its domain can
never be mapped (two clients share gmail.com).

Guards:
 - the company must exist;
 - the email must be a valid address (normalised);
 - an address is unique across all companies — if it's already mapped (to this
   or another company) we say so rather than silently moving it.

## The endpoint

| | |
|---|---|
| URL | `api/system/add_tenant_sender.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 57 |  | read [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |
| 71 |  | **insert** [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) (`tenant_id`, `email`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getTenantById()](/code/functions/getTenantById.md), [normaliseEmailAddress()](/code/functions/normaliseEmailAddress.md)

