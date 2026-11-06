# api/system/delete_tenant_sender.php

> API endpoint - 37 lines

API: Remove a specific sender-address mapping from a company (tenant). POST JSON { id } (the tenant_sender_addresses row id)

Future mail from that address falls back to domain routing (shared intake) or
triage. Nothing is deleted beyond the mapping; existing tickets are untouched.

## The endpoint

| | |
|---|---|
| URL | `api/system/delete_tenant_sender.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | **delete** [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

