# api/files/prefs.php

> API endpoint - 39 lines

POST {desktop_colour?, navbar?, view?, windows?} - the person's own desktop. Desktop colour is the ONLY appearance choice a person has (Ed, 2026-10-10); the logo and its corner are System -> Branding's. navbar and view are module behaviour, windows is remembered sizes and positions.

## The endpoint

| | |
|---|---|
| URL | `api/files/prefs.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiBody()](/code/functions/filesApiBody.md), [filesApiFail()](/code/functions/filesApiFail.md), [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesSavePref()](/code/functions/filesSavePref.md)

