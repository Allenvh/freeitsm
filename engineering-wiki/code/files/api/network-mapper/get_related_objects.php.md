# api/network-mapper/get_related_objects.php

> API endpoint - 155 lines

API: Get all CMDB objects related to a given object, across three buckets: - outgoing: cmdb_object_relationships where this object is from_object_id - incoming: cmdb_object_relationships where this object is to_object_id - property: cmdb_object_properties where value_object_id = this id (other objects referencing this one via an object_ref property)

Used by Network Mapper's "Add related objects" flow to pull CMDB neighbours
onto the canvas. Each returned row carries enough info for the modal to
render (name, class, icon, planned flag) and for the connector to be
provenance-linked back to a real cmdb_object_relationships row when
applicable.

Returns a flat array — one row per (related_object, relationship-path), so
the same object can appear multiple times if it's reachable through more
than one path. The frontend dedupes by (object_id, kind) and shows each
path as a separate tickable row so the user picks which one becomes the
connector.

GET ?object_id=X

## The endpoint

| | |
|---|---|
| URL | `api/network-mapper/get_related_objects.php` |
| Protected by | Module access: network-mapper (requireModuleAccessJson) |
| Reads | `GET object_id` |
| Called from | [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 64 |  | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |
| 94 |  | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |
| 125 |  | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

