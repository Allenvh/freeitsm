# includes/cmdb_impact.php

> PHP - 363 lines

CMDB impact analysis — the ONE implementation of "what breaks if this breaks".

Lives in includes/ rather than api/cmdb/ because the internal endpoint
(api/cmdb/get_object_impact.php) and the public REST API
(api/v1/resources/cmdb.php) both need it. Before this file they each carried
their own copy of the descendants walk.

Two things are computed, and they answer different questions:

  `cmdbDirectImpact()`  — what is attached to this object right now, one hop out,
                        split into the three buckets the UI and the REST API
                        have always returned. Shape is frozen: /cmdb/objects/
                        {id}/impact is a published contract.

  `cmdbBlastRadius()`   — what would ultimately be affected if this object
                        failed, following impact-bearing edges as far as they
                        go. This is the transitive view: a server takes out a
                        VM, which takes out an application, which takes out a
                        customer-facing service.

WHICH EDGES CARRY IMPACT
------------------------
Not every link means "a failure travels this way", and the ones that do don't
all point the same way. Following every edge blindly would report that a
server failure affects the building it is located in.

  Containment (parent_id)  — always carries impact. Parent semantics in this
                             module are ontological dependency (the FK
                             cascade-deletes), so if the parent is gone the
                             children are gone. Direction: parent -> child.

  Relationships            — only when the *type* says so, via
                             cmdb_relationship_types.impact_direction:
                               'none'    ignore it ("is located in")
                               'to_from' the TO object failing affects the
                                         FROM object ("A depends on B")
                               'from_to' the FROM object failing affects the
                                         TO object ("A hosts B")

  object_ref properties    — only when cmdb_class_properties.spreads_impact
                             is set. A dependency recorded as a field rather
                             than a relationship, e.g. a Database whose
                             "Host Server" points at a Server. Direction is
                             always referenced -> holder.

Everything defaults to "does not spread", so an upgraded install reports
exactly what it did before until someone deliberately configures it.

MULTI-COMPANY
-------------
A CI belongs to exactly one company and the model forbids cross-company
links (see the CMDB wiki page). The walk still filters by the root's company
rather than trusting that invariant — one bad row predating the rule would
otherwise let a blast radius enumerate another company's estate, and the
caller has already authorised the root only. NULL tenant_id means the default
company's, so it is resolved before comparing.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [cmdbImpactDirections()](/code/functions/cmdbImpactDirections.md) | 74 | The values impact_direction is allowed to hold. |
| [cmdbHasImpactEdgesConfigured()](/code/functions/cmdbHasImpactEdgesConfigured.md) | 87 | Has anyone actually configured an edge that carries impact? |
| [cmdbImpactTenantScope()](/code/functions/cmdbImpactTenantScope.md) | 107 | Resolve the company a walk is allowed to stay inside, or null when the install is single-company and there is nothing to constrain. |
| [cmdbDirectImpact()](/code/functions/cmdbDirectImpact.md) | 126 | The three one-hop buckets. Shape is a published API contract — add keys here, never rename or remove them. |
| [cmdbBlastRadius()](/code/functions/cmdbBlastRadius.md) | 210 | Transitive blast radius: everything reachable from `$rootId` along impact-bearing edges. Breadth-first, so the depth recorded against each object is the SHORTEST number of hops from the root — which is what the panel groups by, and what makes "2 hops away" meaningful rather than an artefact of traversal order. |
| [cmdbImpactEdgesFrom()](/code/functions/cmdbImpactEdgesFrom.md) | 262 | One hop out from every id in `$fromIds`, across all three edge kinds. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 89 | `cmdbHasImpactEdgesConfigured` | read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 95 | `cmdbHasImpactEdgesConfigured` | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 111 | `cmdbImpactTenantScope` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 133 | `cmdbDirectImpact` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 157 | `cmdbDirectImpact` | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 173 | `cmdbDirectImpact` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 286 | `cmdbImpactEdgesFrom` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 297 | `cmdbImpactEdgesFrom` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 313 | `cmdbImpactEdgesFrom` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 328 | `cmdbImpactEdgesFrom` | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 348 | `cmdbImpactEdgesFrom` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/cmdb/create_impact_diagram.php](/code/files/api/cmdb/create_impact_diagram.php.md), [api/cmdb/get_object_impact.php](/code/files/api/cmdb/get_object_impact.php.md), [api/cmdb/save_relationship_type.php](/code/files/api/cmdb/save_relationship_type.php.md), [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md), [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md)

**Calls:** [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

