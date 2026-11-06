# cmdbAuditRequiredMissing()

A property the class marks required, with no value on the object.

This is legitimately reachable without anyone doing anything wrong: mark an
existing property required and every object created before that moment is
instantly non-compliant. docs/cmdb.md lists that as an open question — block
the change, prompt for a default, or leave them invalid. This answers it:
leave them, and surface them here.

```php
function cmdbAuditRequiredMissing(PDO $conn, int $analystId): array
```

Defined in [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md), line 89.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 91 | `cmdbAuditRequiredMissing` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md), read [cmdb_object_properties](/code/tables/cmdb_object_properties.md) |  |

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md), [cmdbAuditResult()](/code/functions/cmdbAuditResult.md), [cmdbAuditRows()](/code/functions/cmdbAuditRows.md)

## Called from

- [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) - in [cmdbRunAudit()](/code/functions/cmdbRunAudit.md)

