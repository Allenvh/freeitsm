# api/cmdb/save_class.php

> API endpoint - 99 lines

API: Create or update a CMDB class. On create, class_key is auto-generated from the name if blank. On update, class_key is updatable but defaults to the existing value (immutable in spirit — see docs/cmdb.md — though changes are technically allowed).

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/save_class.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_CLASSES (requireCapabilityJson) |
| Called from | [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [slugify()](/code/functions/slugify.md) | 29 | Turns a class name into a lower-case key of letters, digits and underscores, falling back to 'class' when nothing is left. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 |  | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 72 |  | **insert** [cmdb_classes](/code/tables/cmdb_classes.md) (`class_key`, `name`, `description`, `icon_id`, `display_order`, `is_active`, `created_datetime`, `updated_datetime`) |  |
| 81 |  | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 88 |  | **update** [cmdb_classes](/code/tables/cmdb_classes.md) (`class_key`, `name`, `description`, `icon_id`, `display_order`, `is_active`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

