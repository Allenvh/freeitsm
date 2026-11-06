# api/system/add_freemail_domain.php

> API endpoint - 77 lines

API: Add a custom public / free-email domain (add-only — the built-in list can't be edited, so gmail.com & friends can never be un-protected). POST JSON { domain }

Guards:
 - must be a plausible domain (normalised);
 - rejected if it's already a built-in (no-op — already treated as public);
 - rejected if it's already in the custom list;
 - rejected if it's currently mapped to a company (tenant_domains) — a real
   company domain and a public domain are mutually exclusive; remove it there
   first.

## The endpoint

| | |
|---|---|
| URL | `api/system/add_freemail_domain.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [tenant_domains](/code/tables/tenant_domains.md) |  |
| 62 |  | **insert** [freemail_domains](/code/tables/freemail_domains.md) (`domain`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [freemailBuiltinDomains()](/code/functions/freemailBuiltinDomains.md), [getTenantById()](/code/functions/getTenantById.md), [normaliseEmailDomain()](/code/functions/normaliseEmailDomain.md)

