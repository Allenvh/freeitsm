# api/forms/get_form.php

> API endpoint - 100 lines

API: Get a single form with its fields

## The endpoint

| | |
|---|---|
| URL | `api/forms/get_form.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [forms](/code/tables/forms.md), read [analysts](/code/tables/analysts.md) |  |
| 73 |  | read [form_fields](/code/tables/form_fields.md) |  |
| 87 |  | read [forms](/code/tables/forms.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

