# api/software/get_apikeys.php

> API endpoint - 44 lines

API Endpoint: Get API Keys Returns all API keys with owner information

## The endpoint

| | |
|---|---|
| URL | `api/software/get_apikeys.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | read [apikeys](/code/tables/apikeys.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

