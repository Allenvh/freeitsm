# cmdbRunAudit()

Run every check.

```php
function cmdbRunAudit(PDO $conn, int $analystId): array
```

Defined in [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md), line 41.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{checks: array, total_findings: int, objects_examined: int}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 | `cmdbRunAudit` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md), [cmdbAuditBrokenReferences()](/code/functions/cmdbAuditBrokenReferences.md), [cmdbAuditDependencyBlank()](/code/functions/cmdbAuditDependencyBlank.md), [cmdbAuditDisconnected()](/code/functions/cmdbAuditDisconnected.md), [cmdbAuditNoImpactEdges()](/code/functions/cmdbAuditNoImpactEdges.md), [cmdbAuditRequiredMissing()](/code/functions/cmdbAuditRequiredMissing.md), [cmdbAuditStale()](/code/functions/cmdbAuditStale.md)

## Called from

- [api/cmdb/get_audit.php](/code/files/api/cmdb/get_audit.php.md)

