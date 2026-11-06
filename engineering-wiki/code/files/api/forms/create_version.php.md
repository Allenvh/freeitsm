# api/forms/create_version.php

> API endpoint - 35 lines

API: Fork the current form into a new version (#442). Thin UI adapter over FormsService.

POST { parent_form_id } -> { id, version_number }.

## The endpoint

| | |
|---|---|
| URL | `api/forms/create_version.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

