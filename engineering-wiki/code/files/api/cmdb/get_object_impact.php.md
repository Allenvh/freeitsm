# api/cmdb/get_object_impact.php

> API endpoint - 62 lines

API: Compute the "blast radius" for an object — what would be affected if this object went offline / was deleted.

Returns two views of the same estate, computed by includes/cmdb_impact.php:

  impact.*        — the three one-hop buckets (descendants,
                    referenced_by_property, referenced_by_relationship).
                    Shape frozen; the REST API publishes it.
  blast_radius.*  — the TRANSITIVE view: everything ultimately affected,
                    following only edges configured to carry impact, with
                    each object's shortest hop count and how it was reached.

Used by:
  - The Impact panel on the object detail page
  - The AI summary generator (so the prose can say "X databases depend on this")

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/get_object_impact.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Reads | `GET id` |
| Called from | [cmdb/object.js](/code/files/cmdb/object.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md)

**Calls:** [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [cmdbBlastRadius()](/code/functions/cmdbBlastRadius.md), [cmdbDirectImpact()](/code/functions/cmdbDirectImpact.md), [cmdbImpactTenantScope()](/code/functions/cmdbImpactTenantScope.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

