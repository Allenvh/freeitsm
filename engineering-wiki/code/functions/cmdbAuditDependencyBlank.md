# cmdbAuditDependencyBlank()

A property ticked "this is a dependency" that is blank on an object.

Only meaningful because of the impact feature: the install has said this
field records a real dependency, so a blank one is a hole in the blast radius
rather than merely an empty field. Says nothing about properties nobody has
marked as dependencies.

```php
function cmdbAuditDependencyBlank(PDO $conn, int $analystId): array
```

Defined in [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 148 | `cmdbAuditDependencyBlank` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_object_properties](/code/tables/cmdb_object_properties.md) |  |

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md), [cmdbAuditResult()](/code/functions/cmdbAuditResult.md), [cmdbAuditRows()](/code/functions/cmdbAuditRows.md)

## Called from

- [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) - in [cmdbRunAudit()](/code/functions/cmdbRunAudit.md)

