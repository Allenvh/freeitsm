# api/assets/debug_vcenter.php

> API endpoint - 278 lines

Debug Endpoint: Dump ALL raw fields from vCenter REST API Read-only - does NOT write to the database Shows every field returned by the API, including ones not used in get_vcenter.php

## The endpoint

| | |
|---|---|
| URL | `api/assets/debug_vcenter.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_VCENTER (requireCapabilityJson) |
| Reads | `GET vm`, `GET limit` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [vcenterAuth()](/code/functions/vcenterAuth.md) | 192 | Authenticate with vCenter REST API |
| [vcenterGetRaw()](/code/functions/vcenterGetRaw.md) | 230 | Raw GET request - returns decoded JSON without unwrapping 'value' key so we can see the exact API response structure |
| [vcenterLogout()](/code/functions/vcenterLogout.md) | 263 | Logout from vCenter session |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

