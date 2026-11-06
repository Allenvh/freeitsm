# api/tickets/get_triage.php

> API endpoint - 79 lines

API: The triage queue — inbound-email tickets that matched no company.

These are tickets with tenant_id IS NULL: mail that arrived at a shared-intake
mailbox whose sender domain matched none of the registered company domains
(including freemail, which is never mapped). They're the safety net — nothing
is lost; an analyst files each one to a company here.

Returns one row per such ticket with its sender, sender domain and whether that
domain is freemail (so the UI can offer "map this domain" only when safe).

Empty on a single-company install (triage is meaningless with one company).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_triage.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [tickets](/code/tables/tickets.md), read [emails](/code/tables/emails.md), read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [isFreemailDomain()](/code/functions/isFreemailDomain.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

