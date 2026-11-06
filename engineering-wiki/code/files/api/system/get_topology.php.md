# api/system/get_topology.php

> API endpoint - 148 lines

API: Topology / overview — the whole multi-tenant configuration in one shape, for the System -> Topology tree. Company-rooted (companies are the isolation boundary) with a separate "Global / shared" node for things that serve every company (shared mailboxes, global SSO providers, all-access analysts).

Read-only aggregation. Degrades gracefully if a table/column from a newer
migration isn't present yet (returns empty for that slice).

## The endpoint

| | |
|---|---|
| URL | `api/system/get_topology.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | read [tenants](/code/tables/tenants.md) |  |
| 52 |  | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 62 |  | read [tenant_domains](/code/tables/tenant_domains.md) |  |
| 63 |  | read [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |
| 64 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 65 |  | read [analyst_tenant_access](/code/tables/analyst_tenant_access.md), read [analysts](/code/tables/analysts.md) |  |
| 68 |  | read [tickets](/code/tables/tickets.md) |  |
| 71 |  | read [tenant_domains](/code/tables/tenant_domains.md), read [users](/code/tables/users.md) |  |
| 89 |  | read [analysts](/code/tables/analysts.md) |  |
| 140 |  | read [analysts](/code/tables/analysts.md) |  |
| 141 |  | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/mailbox_auth.php](/code/files/includes/mailbox_auth.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [mailboxAuthenticatedSql()](/code/functions/mailboxAuthenticatedSql.md)

