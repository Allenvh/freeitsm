# cmdbAuditDisconnected()

An object attached to nothing: no parent, no children, no relationships.

Not an error — plenty of things legitimately stand alone — but it can never
appear in any blast radius and nobody will reach it by navigating, so it is
the single best indicator of a CMDB drifting into a write-only list.

Ticket links deliberately do NOT rescue an object here. A CI referenced by a
ticket is being used, but it still contributes nothing to impact analysis,
which is what this check is about.

```php
function cmdbAuditDisconnected(PDO $conn, int $analystId): array
```

Defined in [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md), line 177.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 179 | `cmdbAuditDisconnected` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md), [cmdbAuditResult()](/code/functions/cmdbAuditResult.md), [cmdbAuditRows()](/code/functions/cmdbAuditRows.md)

## Called from

- [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) - in [cmdbRunAudit()](/code/functions/cmdbRunAudit.md)

