# workflowEmitWarrantyExpiries()

Asset warranties approaching expiry → `asset.warranty_expiring`.

```php
function workflowEmitWarrantyExpiries(PDO $conn): int
```

Defined in [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md), line 188.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 197 | `workflowEmitWarrantyExpiries` | read [assets](/code/tables/assets.md) |  |

## Calls

[naive_today_sql()](/code/functions/naive_today_sql.md), [workflowEmitOnce()](/code/functions/workflowEmitOnce.md), [workflowExpiryWindows()](/code/functions/workflowExpiryWindows.md)

## Called from

- [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) - in [workflowScheduledRun()](/code/functions/workflowScheduledRun.md)

