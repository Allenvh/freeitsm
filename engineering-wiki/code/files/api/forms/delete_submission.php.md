# api/forms/delete_submission.php

> API endpoint - 27 lines

API: Delete a form submission. Thin UI adapter over FormsService.

## The endpoint

| | |
|---|---|
| URL | `api/forms/delete_submission.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

