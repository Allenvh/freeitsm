# signaturesForAnalyst()

Every signature belonging to one analyst, default first.

```php
function signaturesForAnalyst(PDO $conn, int $analystId): array
```

Defined in [includes/signatures.php](/code/files/includes/signatures.php.md), line 92.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 95 | `signaturesForAnalyst` | read [analyst_signatures](/code/tables/analyst_signatures.md) |  |

## Called from

- [api/myaccount/get_signatures.php](/code/files/api/myaccount/get_signatures.php.md)
- [includes/signatures.php](/code/files/includes/signatures.php.md) - in [defaultSignatureForAnalyst()](/code/functions/defaultSignatureForAnalyst.md)

