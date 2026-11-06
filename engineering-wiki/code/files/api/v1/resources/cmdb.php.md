# api/v1/resources/cmdb.php

> API endpoint - 605 lines

FreeITSM REST API v1 — CMDB resource (configuration items).

Mirrors the module's internal endpoints so an object touched via the API is
indistinguishable from one touched in the UI:
  - object create/update mirror api/cmdb/save_object.php: class is
    immutable after creation, parent links are cycle-checked, required
    properties are enforced on create (and only for touched properties on
    update — inline-edit friendly), and values are stored strongly typed
    with save_object.php's exact per-type validation (numbers numeric,
    object_ref existence + target-class constraint + no self-reference).
  - relationships mirror save/delete_object_relationship.php: no
    self-links, active type required, the unique from/to/type triple
    returns 409.
  - DELETE removes the whole descendant tree with children counted, but
    does it EXPLICITLY (properties, relationships, ticket links, object_ref
    back-references nulled) rather than trusting FK cascades — installs
    grown via Database Verify had no CMDB foreign keys at all.

One deliberate improvement over the UI, documented:
  - dropdown values are validated against the property's option list (422)
    instead of storing anything.

MULTI-TENANCY: configuration items are company-scoped (`cmdb_objects.tenant_id`,
NULL = the Default company). The key's company scope restricts every list and
by-id read/write — apiKeyTenantFilter on the list, apiKeyCanAccessTenantRow via
apiCmdbLoadObject — and a CI outside scope is a 404, never a 403, so the API
never confirms it exists. `?company_id=` narrows the list within what the key
can already see; POST takes company_id, defaulting to the key's own company.
A CI belongs to exactly ONE company: parent links, relationships and
object_ref properties must stay within it (enforced in CmdbService).
Classes, class properties, relationship types and icons remain install-wide
admin config, matching the UI.
There is no audit trail in the product and none is invented here.

Object + relationship WRITES are delegated to CmdbService
(includes/services/cmdb.php); the read handlers, serializers, class defs, and
the (API-only) ticket-link endpoints stay here.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/cmdb.php` |
| Protected by | REST API key |
| Reads | `GET company_id`, `GET class_id`, `GET class_key`, `GET parent_id`, `GET top_level`, `GET is_planned`, `GET q`, `GET sort` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiCmdbClassesList()](/code/functions/apiCmdbClassesList.md) | 56 | GET /cmdb/classes - every CMDB class with its icon and how many objects it holds. |
| [apiCmdbClassesGet()](/code/functions/apiCmdbClassesGet.md) | 85 | GET /cmdb/classes/{id} - one CMDB class with its property definitions and their options. |
| [apiCmdbLoadObject()](/code/functions/apiCmdbLoadObject.md) | 152 | Load one CI; 404 if unknown OR outside the key's companies (multi-tenancy — the key's company scope gates every by-id read/write; a NULL tenant_id normalises to the Default company; no-op at N=1 / all-access key). |
| [apiCmdbSerializeObject()](/code/functions/apiCmdbSerializeObject.md) | 176 | Shapes a CMDB object row for the API - name, company, class, parent, planned flag and dates. |
| [apiCmdbClassDefs()](/code/functions/apiCmdbClassDefs.md) | 195 | Property definitions for a class, keyed by property_key. |
| [apiCmdbObjectsList()](/code/functions/apiCmdbObjectsList.md) | 211 | GET /cmdb/objects |
| [apiCmdbObjectsGet()](/code/functions/apiCmdbObjectsGet.md) | 296 | GET /cmdb/objects/{id} — fully hydrated |
| [apiCmdbObjectsCreate()](/code/functions/apiCmdbObjectsCreate.md) | 416 | POST /cmdb/objects |
| [apiCmdbObjectsUpdate()](/code/functions/apiCmdbObjectsUpdate.md) | 437 | PATCH /cmdb/objects/{id} |
| [apiCmdbObjectsDelete()](/code/functions/apiCmdbObjectsDelete.md) | 448 | DELETE /cmdb/objects/{id} — explicit descendant-tree removal (grown installs have no CMDB FK cascades; see the db_verify FK group added alongside this) |
| [apiCmdbObjectImpact()](/code/functions/apiCmdbObjectImpact.md) | 458 | GET /cmdb/objects/{id}/impact — blast radius (mirrors get_object_impact.php) |
| [apiCmdbRelationshipsCreate()](/code/functions/apiCmdbRelationshipsCreate.md) | 487 | Relationships — POST /cmdb/objects/{id}/relationships, DELETE .../{rel_id} |
| [apiCmdbRelationshipsDelete()](/code/functions/apiCmdbRelationshipsDelete.md) | 500 | DELETE /cmdb/objects/{id}/relationships/{relId} - deletes a relationship through CmdbService. |
| [apiCmdbObjectTicketsList()](/code/functions/apiCmdbObjectTicketsList.md) | 510 | Ticket links — company-scoped on both the CI and the tickets |
| [apiCmdbObjectTicketsLink()](/code/functions/apiCmdbObjectTicketsLink.md) | 544 | POST /cmdb/objects/{id}/tickets - links a ticket to the object. Both must be in the key's companies; a link that already exists answers 409. |
| [apiCmdbObjectTicketsUnlink()](/code/functions/apiCmdbObjectTicketsUnlink.md) | 573 | DELETE /cmdb/objects/{id}/tickets/{ticketId} - removes the link between the object and a ticket, or answers 404 when there is none. |
| [apiCmdbRelationshipTypesList()](/code/functions/apiCmdbRelationshipTypesList.md) | 590 | Reference: relationship types |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 62 | `apiCmdbClassesList` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |
| 87 | `apiCmdbClassesGet` | read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |
| 97 | `apiCmdbClassesGet` | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 107 | `apiCmdbClassesGet` | read [cmdb_class_property_options](/code/tables/cmdb_class_property_options.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 154 | `apiCmdbLoadObject` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [tenants](/code/tables/tenants.md) |  |
| 197 | `apiCmdbClassDefs` | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 272 | `apiCmdbObjectsList` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 278 | `apiCmdbObjectsList` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [tenants](/code/tables/tenants.md) |  |
| 311 | `apiCmdbObjectsGet` | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 313 | `apiCmdbObjectsGet` | read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 358 | `apiCmdbObjectsGet` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 365 | `apiCmdbObjectsGet` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 386 | `apiCmdbObjectsGet` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 396 | `apiCmdbObjectsGet` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 518 | `apiCmdbObjectTicketsList` | read [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 557 | `apiCmdbObjectTicketsLink` | **insert** [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md) (`ticket_id`, `cmdb_object_id`, `created_datetime`, `created_by_analyst_id`) |  |
| 579 | `apiCmdbObjectTicketsUnlink` | **delete** [ticket_cmdb_objects](/code/tables/ticket_cmdb_objects.md) |  |
| 592 | `apiCmdbRelationshipTypesList` | read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md), [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md), [apiKeyCanAccessTicket()](/code/functions/apiKeyCanAccessTicket.md), [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiRespond()](/code/functions/apiRespond.md), [cmdbBlastRadius()](/code/functions/cmdbBlastRadius.md), [cmdbDirectImpact()](/code/functions/cmdbDirectImpact.md), [cmdbImpactTenantScope()](/code/functions/cmdbImpactTenantScope.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

