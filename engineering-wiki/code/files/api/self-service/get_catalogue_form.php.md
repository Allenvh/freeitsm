# api/self-service/get_catalogue_form.php

> API endpoint - 85 lines

API: one catalogue form and its fields, for the portal. GET ?id=

A deliberately NARROWER shape than the analyst's api/forms/get_form.php,
which also returns created_by_name, modified_by_name, version_number and
is_leaf. None of that is a customer's business: who wrote the form and how
many times it has been revised is internal detail.

The visibility gate is in the QUERY, so a form that isn't in the catalogue is
indistinguishable from one that doesn't exist — knowing its id gets you
nothing.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_catalogue_form.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [forms](/code/tables/forms.md) |  |
| 60 |  | read [form_fields](/code/tables/form_fields.md) |  |
| 73 |  | read [forms](/code/tables/forms.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

