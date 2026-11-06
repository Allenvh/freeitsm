# api/software/save_licence.php

> API endpoint - 34 lines

API Endpoint: Save Software Licence. Thin UI adapter over SoftwareService — creates or updates a licence record.

## The endpoint

| | |
|---|---|
| URL | `api/software/save_licence.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/software.php](/code/files/includes/services/software.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

