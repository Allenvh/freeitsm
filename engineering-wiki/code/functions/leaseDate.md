# leaseDate()

Validates an optional YYYY-MM-DD date; blank gives null, anything else invalid throws a validation error naming `$label`.

```php
function leaseDate($v, string $label): ?string
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 181.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | untrusted input; null or '' gives null, otherwise only the first 10 characters are checked |
| `$label` | `string` |  | the field's name, used in the validation message |

**Returns:** `?string`

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseScheduleSave()](/code/functions/leaseScheduleSave.md), [leaseAssetUpdate()](/code/functions/leaseAssetUpdate.md)

