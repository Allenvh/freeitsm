# api/tickets/get_csat_settings.php

> API endpoint - 36 lines

API: Get CSAT settings Returns the four user-facing CSAT keys from system_settings. The HMAC secret is deliberately NOT returned to the client.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_csat_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

