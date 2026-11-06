# api/system/get_tenant_senders.php

> API endpoint - 43 lines

API: List the specific sender addresses mapped to a company (tenant). GET ?tenant_id=N — returns [{ id, email }] for shared-intake routing.

The address-level twin of get_tenant_domains.php. "Company" is the
user-facing word for a tenant; the table/code stays `tenant`.

## The endpoint

| | |
|---|---|
| URL | `api/system/get_tenant_senders.php` |
| Protected by | Module access: system (requireModuleAccessJson) |
| Reads | `GET tenant_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

