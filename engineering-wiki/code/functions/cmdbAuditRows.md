# cmdbAuditRows()

Normalise ids to ints for the JSON.

```php
function cmdbAuditRows(PDOStatement $st): array
```

Defined in [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md), line 243.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$st` | `PDOStatement` |  | an executed finding query whose rows include an object_id column |

**Returns:** `array`

## Called from

- [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) - in [cmdbAuditRequiredMissing()](/code/functions/cmdbAuditRequiredMissing.md), [cmdbAuditBrokenReferences()](/code/functions/cmdbAuditBrokenReferences.md), [cmdbAuditDependencyBlank()](/code/functions/cmdbAuditDependencyBlank.md), [cmdbAuditDisconnected()](/code/functions/cmdbAuditDisconnected.md), [cmdbAuditStale()](/code/functions/cmdbAuditStale.md)

