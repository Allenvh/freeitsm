# recordPreviewChange()

── Change ──────────────────────────────────────────────────────────────────

```php
function recordPreviewChange(PDO $conn, int $analystId, int $id): ?array
```

Defined in [includes/record_preview.php](/code/files/includes/record_preview.php.md), line 222.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the changes id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 231 | `recordPreviewChange` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |

## Calls

[analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [rpField()](/code/functions/rpField.md), [rpFields()](/code/functions/rpFields.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

