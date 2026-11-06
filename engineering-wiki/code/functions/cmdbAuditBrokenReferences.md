# cmdbAuditBrokenReferences()

An object reference pointing at an object that no longer exists.

Genuine dangling data rather than an omission, which is why this is the only
check rated as an error. Installs grown through Database Verification have no
CMDB foreign keys at all, so nothing at the database level prevents it.

```php
function cmdbAuditBrokenReferences(PDO $conn, int $analystId): array
```

Defined in [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md), line 118.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 120 | `cmdbAuditBrokenReferences` | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md), [cmdbAuditResult()](/code/functions/cmdbAuditResult.md), [cmdbAuditRows()](/code/functions/cmdbAuditRows.md)

## Called from

- [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) - in [cmdbRunAudit()](/code/functions/cmdbRunAudit.md)

