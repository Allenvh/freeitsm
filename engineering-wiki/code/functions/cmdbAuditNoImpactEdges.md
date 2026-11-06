# cmdbAuditNoImpactEdges()

Install-level: nothing at all is configured to carry impact, so every blast radius on every object is empty regardless of how good the data is.

Not per-object, so it reports 1 or 0 rather than a list. Worth its own check
because it makes every other finding here moot — there is no point tidying
dependencies that nothing will ever follow.

```php
function cmdbAuditNoImpactEdges(PDO $conn): array
```

Defined in [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md), line 232.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[cmdbAuditResult()](/code/functions/cmdbAuditResult.md), [cmdbHasImpactEdgesConfigured()](/code/functions/cmdbHasImpactEdgesConfigured.md)

## Called from

- [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) - in [cmdbRunAudit()](/code/functions/cmdbRunAudit.md)

