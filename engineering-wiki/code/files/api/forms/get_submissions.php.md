# api/forms/get_submissions.php

> API endpoint - 130 lines

API: Get submissions for a form

## The endpoint

| | |
|---|---|
| URL | `api/forms/get_submissions.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |
| Reads | `GET form_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [forms](/code/tables/forms.md) |  |
| 62 |  | read [form_fields](/code/tables/form_fields.md) |  |
| 75 |  | read [form_submissions](/code/tables/form_submissions.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md), read [tickets](/code/tables/tickets.md) |  |
| 103 |  | read [form_submission_data](/code/tables/form_submission_data.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

