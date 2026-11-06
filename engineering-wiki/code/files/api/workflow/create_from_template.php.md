# api/workflow/create_from_template.php

> API endpoint - 65 lines

API: Clone a starter template into a real workflow for this install.

Body: { key }

The new workflow is always created INACTIVE — a recipe must be read and
switched on deliberately, never fire the moment it is cloned.

Returns the new id plus `unresolved`: the list of args the template could
not fill in for this install (a priority it doesn't have, a webhook URL
only the user knows). The editor turns that into a banner.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/create_from_template.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | **insert** [workflows](/code/tables/workflows.md) (`name`, `description`, `trigger_event`, `conditions`, `actions`, `is_active`, `created_by`, `created_datetime`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [workflow/includes/templates.php](/code/files/workflow/includes/templates.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

