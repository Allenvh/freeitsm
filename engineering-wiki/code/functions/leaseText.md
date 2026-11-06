# leaseText()

Trims optional text and cuts it to `$max` characters; blank gives null.

```php
function leaseText($v, int $max): ?string
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 198.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | untrusted input; null or blank after trimming gives null |
| `$max` | `int` |  | the most characters kept |

**Returns:** `?string`

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSave()](/code/functions/leaseSave.md), [leaseScheduleSave()](/code/functions/leaseScheduleSave.md), [leaseAssetUpdate()](/code/functions/leaseAssetUpdate.md)

