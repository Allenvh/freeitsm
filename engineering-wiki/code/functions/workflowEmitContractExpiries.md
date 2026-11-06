# workflowEmitContractExpiries()

Contracts approaching their end date → `contract.expiring`.

```php
function workflowEmitContractExpiries(PDO $conn): int
```

Defined in [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md), line 118.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 135 | `workflowEmitContractExpiries` | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md) |  |

## Calls

[contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartySql()](/code/functions/contractPartySql.md), [naive_today_sql()](/code/functions/naive_today_sql.md), [workflowEmitOnce()](/code/functions/workflowEmitOnce.md), [workflowExpiryWindows()](/code/functions/workflowExpiryWindows.md)

## Called from

- [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) - in [workflowScheduledRun()](/code/functions/workflowScheduledRun.md)

