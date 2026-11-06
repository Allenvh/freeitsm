# api/domains/process.php

> API endpoint - 40 lines

POST {ids:[…], lookup?:bool, check?:bool} — look up and/or check up to ten domains now. The screen calls this in small batches after a bulk add, or for "Refresh selected", and draws a progress bar between calls; ten keeps each request well inside a web server's time limit.

## The endpoint

| | |
|---|---|
| URL | `api/domains/process.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md)

**Calls:** [domainApiBody()](/code/functions/domainApiBody.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainLookupPace()](/code/functions/domainLookupPace.md), [domainSetting()](/code/functions/domainSetting.md)

