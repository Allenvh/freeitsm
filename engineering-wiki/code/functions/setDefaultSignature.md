# setDefaultSignature()

Make exactly one signature the default for an analyst.

Two statements, not one: clearing every flag and then setting one is what keeps
"exactly one" true even if a previous write left two set. The analyst_id clause on
both is what stops this touching anybody else's signatures.

```php
function setDefaultSignature(PDO $conn, int $analystId, int $signatureId): void
```

Defined in [includes/signatures.php](/code/files/includes/signatures.php.md), line 138.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$signatureId` | `int` |  | the signature (analyst_signatures.id) to make the default; one of another analyst's matches nothing |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 140 | `setDefaultSignature` | **update** [analyst_signatures](/code/tables/analyst_signatures.md) (`is_default`) |  |
| 142 | `setDefaultSignature` | **update** [analyst_signatures](/code/tables/analyst_signatures.md) (`is_default`) |  |

## Called from

- [api/myaccount/save_signature.php](/code/files/api/myaccount/save_signature.php.md)

