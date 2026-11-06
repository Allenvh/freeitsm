# api/system/get_freemail_domains.php

> API endpoint - 41 lines

API: List public / free-email domains. GET — returns the built-in list (always-on, read-only) and the admin-added custom list ([{ id, domain }], removable). See includes/tenancy.php.

## The endpoint

| | |
|---|---|
| URL | `api/system/get_freemail_domains.php` |
| Protected by | Module access: system (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [freemail_domains](/code/tables/freemail_domains.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [freemailBuiltinDomains()](/code/functions/freemailBuiltinDomains.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

