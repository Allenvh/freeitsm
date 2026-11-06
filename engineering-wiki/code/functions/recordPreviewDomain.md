# recordPreviewDomain()

── Domain (#154) ───────────────────────────────────────────────────────────

```php
function recordPreviewDomain(PDO $conn, int $analystId, int $id): ?array
```

Defined in [includes/record_preview.php](/code/files/includes/record_preview.php.md), line 401.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domains id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 407 | `recordPreviewDomain` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md), read [analysts](/code/tables/analysts.md), read [suppliers](/code/tables/suppliers.md) |  |

## Calls

[analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [rpField()](/code/functions/rpField.md), [rpFields()](/code/functions/rpFields.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

