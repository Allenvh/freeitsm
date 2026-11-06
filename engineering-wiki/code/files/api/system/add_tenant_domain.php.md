# api/system/add_tenant_domain.php

> API endpoint - 82 lines

API: Register an email domain to a company (tenant) for shared-intake routing. POST JSON { tenant_id, domain }

Guards:
 - the company must exist;
 - the domain must be a plausible domain (normalised);
 - free-email/consumer domains (gmail.com, …) are REJECTED — two clients can
   share them, so they must never be mapped (they reach support via triage);
 - a domain is unique across all companies — if it's already mapped (to this or
   another company) we say so rather than silently moving it.

## The endpoint

| | |
|---|---|
| URL | `api/system/add_tenant_domain.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 |  | read [tenant_domains](/code/tables/tenant_domains.md) |  |
| 74 |  | **insert** [tenant_domains](/code/tables/tenant_domains.md) (`tenant_id`, `domain`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getTenantById()](/code/functions/getTenantById.md), [isFreemailDomain()](/code/functions/isFreemailDomain.md), [normaliseEmailDomain()](/code/functions/normaliseEmailDomain.md)

