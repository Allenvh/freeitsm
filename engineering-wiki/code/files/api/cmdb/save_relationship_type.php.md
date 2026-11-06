# api/cmdb/save_relationship_type.php

> API endpoint - 77 lines

API: Create or update a CMDB relationship type.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/save_relationship_type.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Permission: Cap::CMDB_RELATIONSHIP_TYPES (requireCapabilityJson) |
| Called from | [cmdb/settings/settings.js](/code/files/cmdb/settings/settings.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 57 |  | **insert** [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) (`verb`, `inverse_verb`, `description`, `impact_direction`, `display_order`, `is_active`, `created_datetime`) |  |
| 66 |  | **update** [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) (`verb`, `inverse_verb`, `description`, `impact_direction`, `display_order`, `is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md)

**Calls:** [cmdbImpactDirections()](/code/functions/cmdbImpactDirections.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

