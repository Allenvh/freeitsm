# api/watchtower/get_dashboard_ext.php

> API endpoint - 146 lines

API: Watchtower Dashboard (Extension) — API-key-authenticated endpoint GET — Returns attention items from every module in a single response Used by the FreeITSM Watchtower browser extension

## The endpoint

| | |
|---|---|
| URL | `api/watchtower/get_dashboard_ext.php` |
| Protected by | Signed-in analyst (session check) (via includes/timezone.php) |
| Called from | [browser-extension/background.js](/code/files/browser-extension/background.js.md), [browser-extension/options.js](/code/files/browser-extension/options.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 |  | read [apikeys](/code/tables/apikeys.md) |  |
| 77 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 92 |  | **insert** [api_rate_limits](/code/tables/api_rate_limits.md) (`apikey_id`, `request_count`, `window_start`) |  |
| 100 |  | read [api_rate_limits](/code/tables/api_rate_limits.md) |  |
| 106 |  | **delete** [api_rate_limits](/code/tables/api_rate_limits.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getWatchtowerData()](/code/functions/getWatchtowerData.md)

