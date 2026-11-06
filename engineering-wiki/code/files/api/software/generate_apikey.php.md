# api/software/generate_apikey.php

> API endpoint - 45 lines

API Endpoint: Generate new API Key Creates a new random API key, bound to the current analyst

## The endpoint

| | |
|---|---|
| URL | `api/software/generate_apikey.php` |
| Protected by | Module access: software (requireModuleAccessJson); Permission: Cap::SOFTWARE_API_KEYS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | **insert** [apikeys](/code/tables/apikeys.md) (`apikey`, `analyst_id`, `label`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

