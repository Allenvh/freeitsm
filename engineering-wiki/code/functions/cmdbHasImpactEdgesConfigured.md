# cmdbHasImpactEdgesConfigured()

Has anyone actually configured an edge that carries impact?

Needed because an empty blast radius is ambiguous: "nothing depends on this"
and "nothing in this install is set to spread impact yet" look identical, and
they call for completely different responses from the reader. An install that
upgraded into this feature starts with everything set to 'none', so without
this the panel would look broken rather than unconfigured.

```php
function cmdbHasImpactEdgesConfigured(PDO $conn): bool
```

Defined in [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md), line 87.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 89 | `cmdbHasImpactEdgesConfigured` | read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |
| 95 | `cmdbHasImpactEdgesConfigured` | read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |

## Called from

- [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) - in [cmdbAuditNoImpactEdges()](/code/functions/cmdbAuditNoImpactEdges.md)
- [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md) - in [cmdbBlastRadius()](/code/functions/cmdbBlastRadius.md)

