# api/forms/get_forms.php

> API endpoint - 80 lines

API: Get all forms with field count and submission count

## The endpoint

| | |
|---|---|
| URL | `api/forms/get_forms.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [form_audiences](/code/tables/form_audiences.md) |  |
| 50 |  | read [form_collections](/code/tables/form_collections.md) |  |
| 60 |  | read [form_fields](/code/tables/form_fields.md), read [form_submissions](/code/tables/form_submissions.md) |  |
| 62 |  | read [forms](/code/tables/forms.md), read [analysts](/code/tables/analysts.md) |  |
| 66 |  | read [forms](/code/tables/forms.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

