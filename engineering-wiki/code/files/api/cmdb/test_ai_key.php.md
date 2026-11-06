# api/cmdb/test_ai_key.php

> API endpoint - 92 lines

API: Test the CMDB Anthropic key with a 1-token round-trip.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/test_ai_key.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_AI (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

