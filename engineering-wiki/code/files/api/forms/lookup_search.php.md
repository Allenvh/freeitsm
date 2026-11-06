# api/forms/lookup_search.php

> API endpoint - 95 lines

Search a lookup field's source, scoped to whoever is asking.

Two callers with genuinely different rights, which is why the scope is worked
out HERE rather than inside the service:

  an analyst     → the companies they can access
  a portal user  → their own company, and only if the FIELD says so

⚠️ The field id is required and the source is read FROM THE FIELD, never from
the request. Letting the caller name the source would make "which laptop?"
into "search the staff directory" with one edited parameter.

GET ?field_id=12&q=lt-00

## The endpoint

| | |
|---|---|
| URL | `api/forms/lookup_search.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET field_id`, `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 47 |  | read [form_fields](/code/tables/form_fields.md), read [forms](/code/tables/forms.md) |  |
| 82 |  | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

