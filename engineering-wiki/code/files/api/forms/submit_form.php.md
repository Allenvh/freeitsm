# api/forms/submit_form.php

> API endpoint - 34 lines

API: Submit a filled-in form. Thin UI adapter over FormsService (which fires the form.submitted workflow).

## The endpoint

| | |
|---|---|
| URL | `api/forms/submit_form.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

