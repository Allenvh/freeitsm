# api/cmdb/create_impact_diagram.php

> API endpoint - 154 lines

API: Turn an object's blast radius into a Network Mapper diagram.

WHY THIS RATHER THAN A SECOND GRAPH
-----------------------------------
The obvious way to visualise a blast radius is to draw one, on the object
page. That would mean a second node-graph renderer one module away from the
one that already exists — and the read-only picture would evaporate the
moment you navigated away.

Network Mapper already draws, saves, versions and exports diagrams of exactly
these objects. So the blast radius hands off to it instead: same data, no new
renderer, no charting dependency, and the result is an artefact you can
rearrange, annotate and attach to a change or an incident review.

The two modules stay distinct in purpose. Network Mapper authors a picture a
human composes; the blast radius answers a question about right now. This
endpoint is the bridge — it gives the authoring tool a sensible starting
layout for a question that has just been answered.

WHAT IT DOES
------------
Runs the walk, lays the result out in rings by hop distance (so the geometry
carries the same meaning the grouped list does), and creates the diagram
through NetworkMapperService so every existing write rule still applies.
Connectors follow the impact edges and carry cmdb_relationship_id where the
edge is a real relationship row, so provenance matches what the module's own
"add related objects" flow produces.

POST { object_id: N }  ->  { success, diagram_id }

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/create_impact_diagram.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson); Module access: network-mapper (requireModuleAccessJson) |
| Called from | [cmdb/object.js](/code/files/cmdb/object.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 75 |  | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md)

**Calls:** [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [cmdbBlastRadius()](/code/functions/cmdbBlastRadius.md), [cmdbImpactTenantScope()](/code/functions/cmdbImpactTenantScope.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

