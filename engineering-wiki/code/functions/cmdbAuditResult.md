# cmdbAuditResult()

Shape every check returns, so the UI can render them uniformly.

```php
function cmdbAuditResult(string $key, string $severity, int $count, array $items): array
```

Defined in [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md), line 70.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the check's identifier, e.g. 'broken_reference', 'stale' |
| `$severity` | `string` |  | 'error', 'warning' or 'info' |
| `$count` | `int` |  | the true number of findings, which may exceed the items listed |
| `$items` | `array` |  | up to CMDB_AUDIT_MAX_ITEMS finding rows (object_id, object_name, class_name, detail) |

**Returns:** `array`

## Called from

- [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) - in [cmdbAuditRequiredMissing()](/code/functions/cmdbAuditRequiredMissing.md), [cmdbAuditBrokenReferences()](/code/functions/cmdbAuditBrokenReferences.md), [cmdbAuditDependencyBlank()](/code/functions/cmdbAuditDependencyBlank.md), [cmdbAuditDisconnected()](/code/functions/cmdbAuditDisconnected.md), [cmdbAuditStale()](/code/functions/cmdbAuditStale.md), [cmdbAuditNoImpactEdges()](/code/functions/cmdbAuditNoImpactEdges.md)

