# rpKnownDepartments()

The departments analysts actually have, for the share picker.

```php
function rpKnownDepartments(PDO $conn): array
```

Defined in [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md), line 250.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 252 | `rpKnownDepartments` | read [analysts](/code/tables/analysts.md) |  |

## Calls

[rpNormDept()](/code/functions/rpNormDept.md)

## Called from

- [api/reporting/packs/shares.php](/code/files/api/reporting/packs/shares.php.md)

