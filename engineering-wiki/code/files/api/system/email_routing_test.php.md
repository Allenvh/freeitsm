# api/system/email_routing_test.php

> API endpoint - 210 lines

API: Inbound-email routing dry-run (diagnostic).

Given a sender address and the mailbox an email would arrive at, work out
where a NEW ticket would be filed — which company, or triage — and WHICH
routing rule decided it. Creates nothing; reads only.

The authoritative answer comes from `resolveTicketTenantForEmail()` in
includes/tenancy.php — the very function the live importer uses — so this
tool can never drift from real behaviour. Around that we assemble a
human-readable step trace from the same evidence (the mailbox's pinned/shared
state and the sender-domain lookup) purely to explain the result.

The "reply to an existing ticket inherits its company" rule (step 1 of the
design's ordered routing) happens upstream of `resolveTicketTenantForEmail()`
and depends on whether the subject carries a ticket reference — so it can't be
simulated from a From + mailbox alone; it's shown as "not evaluated here".

GET ?mailbox_id=N&from_address=jane@acme.com

## The endpoint

| | |
|---|---|
| URL | `api/system/email_routing_test.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET mailbox_id`, `GET from_address` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 96 |  | read [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |
| 107 |  | read [tenant_domains](/code/tables/tenant_domains.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/mailbox_auth.php](/code/files/includes/mailbox_auth.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [getTenantById()](/code/functions/getTenantById.md), [isFreemailDomain()](/code/functions/isFreemailDomain.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [mailboxAuthenticatedSql()](/code/functions/mailboxAuthenticatedSql.md), [normaliseEmailDomain()](/code/functions/normaliseEmailDomain.md), [resolveTicketTenantForEmail()](/code/functions/resolveTicketTenantForEmail.md)

