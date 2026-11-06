# api/software/toggle_apikey.php

> API endpoint - 51 lines

API Endpoint: Toggle API Key active status (revoke/activate)

## The endpoint

| | |
|---|---|
| URL | `api/software/toggle_apikey.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | **update** [apikeys](/code/tables/apikeys.md) (`active`) |  |
| 35 |  | read [apikeys](/code/tables/apikeys.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

