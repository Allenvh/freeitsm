# api/software/delete_apikey.php

> API endpoint - 44 lines

API Endpoint: Delete API Key

## The endpoint

| | |
|---|---|
| URL | `api/software/delete_apikey.php` |
| Protected by | Module access: software (requireModuleAccessJson); Permission: Cap::SOFTWARE_API_KEYS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | **delete** [apikeys](/code/tables/apikeys.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

