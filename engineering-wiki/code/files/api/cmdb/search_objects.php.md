# api/cmdb/search_objects.php

> API endpoint - 79 lines

API: Search objects for autocomplete pickers.

Used by:
  - Parent picker (no class_id filter, optionally exclude self)
  - object_ref property pickers (class_id = the property's target_class_id)
  - Relationship target pickers (no class_id filter, exclude self)

Query params:
  q         — text to substring-match against name (required, min 1 char)
  class_id  — optional: only return objects of this class
  exclude_id — optional: skip this object (e.g. exclude self for parent picker)
  limit     — optional cap, default 20, max 50

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/search_objects.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Reads | `GET q`, `GET class_id`, `GET exclude_id`, `GET limit` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md), [cmdb/browse.js](/code/files/cmdb/browse.js.md), [cmdb/object.js](/code/files/cmdb/object.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

