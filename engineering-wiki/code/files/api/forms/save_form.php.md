# api/forms/save_form.php

> API endpoint - 33 lines

API: Create or update a form with its fields. Thin UI adapter over FormsService.

## The endpoint

| | |
|---|---|
| URL | `api/forms/save_form.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

