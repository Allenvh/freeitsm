# api/knowledge/get_analysts.php

> API endpoint - 31 lines

API Endpoint: Get analysts for owner dropdown

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/get_analysts.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 20 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

