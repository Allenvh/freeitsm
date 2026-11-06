# projectProposalApprovalColumn()

Proposals waiting for a decision, per project id - for the portfolio's view and its chip.

```php
function projectProposalApprovalColumn(PDO $conn): string
```

Defined in [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), line 140.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[projectIntakeReady()](/code/functions/projectIntakeReady.md)

## Called from

- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectListRows()](/code/functions/projectListRows.md)

