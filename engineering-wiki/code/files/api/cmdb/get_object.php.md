# api/cmdb/get_object.php

> API endpoint - 226 lines

API: Get a single CMDB object with everything hydrated: - class info - parent + parent class - children (id + name + class) - properties (with type-aware values; object_ref values include the linked object's name and class so the frontend can render them as cards/links) - relationships (outgoing AND incoming, with verb / inverse_verb and the other side's name+class hydrated)

Single round-trip is the goal — the detail page renders from this payload
without needing follow-up calls.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/get_object.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Reads | `GET id` |
| Called from | [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md), [cmdb/object.js](/code/files/cmdb/object.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 |  | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 66 |  | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 83 |  | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 96 |  | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 108 |  | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 112 |  | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 127 |  | read [cmdb_class_property_options](/code/tables/cmdb_class_property_options.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 187 |  | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 204 |  | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

