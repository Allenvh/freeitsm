# includes/cmdb_audit.php

> PHP - 249 lines

CMDB data-quality audit — "is this CMDB still telling you the truth?"

A CMDB rots quietly. Nothing errors when a record goes stale, loses the field
that made it useful, or ends up connected to nothing; it simply stops being
worth opening, and by the time anyone notices, nobody trusts it. That is the
usual reason these modules get abandoned.

The checks are deliberately framed around whether the data can still answer
the question the module exists to answer — what breaks if this breaks. A CI
connected to nothing is not a stylistic problem, it is a CI that can never
appear in anyone's blast radius.

DESIGN
------
Every check is derived from declarations that already exist — a property
marked required, a property ticked as a dependency, a relationship type set
to carry impact. There is no rule-builder and no new schema: the install has
already stated its intent, and the audit only reports where reality has
drifted from it. (iTop's equivalent lets you write the rules; that is a much
bigger feature and it can come later without breaking this one.)

Findings are advisory. Nothing here edits, blocks or "fixes" anything — the
analyst is the one who knows whether a standalone CI is a mistake or simply a
standalone CI.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [cmdbRunAudit()](/code/functions/cmdbRunAudit.md) | 41 | Run every check. |
| [cmdbAuditResult()](/code/functions/cmdbAuditResult.md) | 70 | Shape every check returns, so the UI can render them uniformly. |
| [cmdbAuditRequiredMissing()](/code/functions/cmdbAuditRequiredMissing.md) | 89 | A property the class marks required, with no value on the object. |
| [cmdbAuditBrokenReferences()](/code/functions/cmdbAuditBrokenReferences.md) | 118 | An object reference pointing at an object that no longer exists. |
| [cmdbAuditDependencyBlank()](/code/functions/cmdbAuditDependencyBlank.md) | 146 | A property ticked "this is a dependency" that is blank on an object. |
| [cmdbAuditDisconnected()](/code/functions/cmdbAuditDisconnected.md) | 177 | An object attached to nothing: no parent, no children, no relationships. |
| [cmdbAuditStale()](/code/functions/cmdbAuditStale.md) | 204 | Nothing has touched this object in CMDB_AUDIT_STALE_MONTHS. |
| [cmdbAuditNoImpactEdges()](/code/functions/cmdbAuditNoImpactEdges.md) | 232 | Install-level: nothing at all is configured to carry impact, so every blast radius on every object is empty regardless of how good the data is. |
| [cmdbAuditRows()](/code/functions/cmdbAuditRows.md) | 243 | Normalise ids to ints for the JSON. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 | `cmdbRunAudit` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 91 | `cmdbAuditRequiredMissing` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_object_properties](/code/tables/cmdb_object_properties.md) |  |
| 120 | `cmdbAuditBrokenReferences` | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 148 | `cmdbAuditDependencyBlank` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_object_properties](/code/tables/cmdb_object_properties.md) |  |
| 179 | `cmdbAuditDisconnected` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 206 | `cmdbAuditStale` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md)

**Included by:** [api/cmdb/get_audit.php](/code/files/api/cmdb/get_audit.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [cmdbHasImpactEdgesConfigured()](/code/functions/cmdbHasImpactEdgesConfigured.md)

