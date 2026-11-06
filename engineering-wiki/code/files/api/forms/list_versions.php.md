# api/forms/list_versions.php

> API endpoint - 101 lines

API: List every version in the chain containing the requested form (#442). Walks back to the root (parent_form_id IS NULL) then forward collecting children. Returns the chain in version_number order (oldest first) so the dropdown can show v1 → v2 → v3 in sequence.

GET ?id=<form_id>
Returns { success, versions: [{ id, parent_form_id, title,
          description, version_number, created_by_name,
          created_date, modified_by_name, modified_date,
          is_current }] }

is_current is true for exactly one row — the leaf with no children.

## The endpoint

| | |
|---|---|
| URL | `api/forms/list_versions.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [forms](/code/tables/forms.md) |  |
| 57 |  | read [forms](/code/tables/forms.md) |  |
| 68 |  | read [forms](/code/tables/forms.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

