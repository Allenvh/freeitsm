# signatureMergeData()

The values a signature can merge, for the analyst who is signing.

Empty strings rather than nulls: a missing phone number should leave a blank in the
signature, not the literal word "null" in an email to a customer.

```php
function signatureMergeData(PDO $conn, int $analystId): array
```

Defined in [includes/signatures.php](/code/files/includes/signatures.php.md), line 35.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 | `signatureMergeData` | read [analysts](/code/tables/analysts.md) |  |

## Called from

- [includes/signatures.php](/code/files/includes/signatures.php.md) - in [renderSignature()](/code/functions/renderSignature.md)

