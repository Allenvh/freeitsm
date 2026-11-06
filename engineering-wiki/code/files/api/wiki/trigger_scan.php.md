# api/wiki/trigger_scan.php

> API endpoint - 42 lines

API Endpoint: Trigger a codebase rescan POST request - launches the PowerShell scanner as a background process

## The endpoint

| | |
|---|---|
| URL | `api/wiki/trigger_scan.php` |
| Protected by | Module access: wiki (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

