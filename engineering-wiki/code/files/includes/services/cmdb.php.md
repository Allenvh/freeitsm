# includes/services/cmdb.php

> PHP - 707 lines

CmdbService — the shared write rules for CMDB configuration items: object create/update (with the typed property system), object delete (descendant tree), and relationship create/delete.

Shared by the UI endpoints (api/cmdb/*.php) and the REST API
(api/v1/resources/cmdb.php). Each caller passes an ActorContext + canonical
input; this layer validates + writes and returns the affected id(s) or throws
ServiceError. It never emits HTTP.

SCOPE: objects + relationships (the genuinely-duplicated overlaps). Classes /
property definitions / relationship types are UI-only admin settings (the API
exposes classes + rel-types read-only), and CMDB↔ticket links are API-only
(no UI twin) — both stay on their own code.

Canonical behaviour = the API resource's: class is immutable after creation,
parents are cycle-checked, required properties are enforced on create (and on
touched properties on update — inline-edit friendly), and property values are
stored strongly typed with per-type validation (numbers numeric, dropdowns
against the option list, dates parsed, object_ref existence + target-class +
no self-reference). Delete removes the whole descendant tree explicitly.

⚠️ The TYPING rules themselves (what a 'number' accepts, how a date is parsed,
what a reference must point at) live in includes/typed_fields.php, shared with
the flexible asset fields. This file decides only WHICH properties apply to a
class and WHERE their values are stored — plus every authorisation rule, which
deliberately never moves into a general-purpose layer. See
docs/design/flexible-asset-fields.md §7.1.

Property input — two accepted shapes (the service normalises both):
  - `properties`: { property_key: value }   (the API's canonical map)
  - `property_values`: [{ property_id, value }]   (the UI's id-addressed list;
    entries whose property_id isn't in the class are dropped, as the UI did)

## Classes

- [CmdbService](/code/classes/CmdbService.md) (class, line 42)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 109 | `CmdbService::assertSameCompany` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 160 | `CmdbService::createObject` | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 163 | `CmdbService::createObject` | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 201 | `CmdbService::createObject` | **insert** [cmdb_objects](/code/tables/cmdb_objects.md) (`class_id`, `name`, `parent_id`, `is_planned`, `tenant_id`, `created_datetime`, `updated_datetime`) |  |
| 267 | `CmdbService::updateObject` | **update** [cmdb_objects](/code/tables/cmdb_objects.md) (`name`, `parent_id`, `is_planned`, `updated_datetime`) |  |
| 270 | `CmdbService::updateObject` | **update** [cmdb_objects](/code/tables/cmdb_objects.md) (`name`, `parent_id`, `updated_datetime`) |  |
| 301 | `CmdbService::deleteObject` | **update** [cmdb_object_properties](/code/tables/cmdb_object_properties.md) (`value_object_id`) |  |
| 302 | `CmdbService::deleteObject` | **delete** [cmdb_object_properties](/code/tables/cmdb_object_properties.md) |  |
| 304 | `CmdbService::deleteObject` | **update** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) (`cmdb_relationship_id`), read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 307 | `CmdbService::deleteObject` | **delete** [network_diagram_connectors](/code/tables/network_diagram_connectors.md), read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 310 | `CmdbService::deleteObject` | **delete** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 311 | `CmdbService::deleteObject` | **delete** [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 312 | `CmdbService::deleteObject` | **delete** [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md) |  |
| 314 | `CmdbService::deleteObject` | **delete** [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 354 | `CmdbService::createRelationship` | read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 357 | `CmdbService::createRelationship` | read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 369 | `CmdbService::createRelationship` | **insert** [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) (`from_object_id`, `to_object_id`, `relationship_type_id`, `created_datetime`) |  |
| 393 | `CmdbService::deleteRelationship` | **delete** [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 401 | `CmdbService::deleteRelationship` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 408 | `CmdbService::deleteRelationship` | **delete** [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |
| 424 | `CmdbService::tenantOf` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 481 | `CmdbService::loadObjectRow` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 509 | `CmdbService::classDefs` | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 577 | `CmdbService::normaliseProperties` | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 601 | `CmdbService::propertyOptionValues` | read [cmdb_class_property_options](/code/tables/cmdb_class_property_options.md) |  |
| 654 | `CmdbService::validateParent` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 666 | `CmdbService::validateParent` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 686 | `CmdbService::descendantIds` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/typed_fields.php](/code/files/includes/typed_fields.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Included by:** [api/cmdb/delete_object.php](/code/files/api/cmdb/delete_object.php.md), [api/cmdb/delete_object_relationship.php](/code/files/api/cmdb/delete_object_relationship.php.md), [api/cmdb/save_object.php](/code/files/api/cmdb/save_object.php.md), [api/cmdb/save_object_relationship.php](/code/files/api/cmdb/save_object_relationship.php.md), [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md)

**Calls:** [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

