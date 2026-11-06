# dsyncDnIsExcluded()

Does this DN sit inside any carved-out branch?

```php
function dsyncDnIsExcluded(string $dn, array $excludes): bool
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 349.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dn` | `string` |  | the entry's distinguished name |
| `$excludes` | `array` |  | carved-out branch DNs, as `dsyncScopes()` returns them |

**Returns:** `bool`

## Calls

[dsyncDnIsUnder()](/code/functions/dsyncDnIsUnder.md)

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncFetchPeople()](/code/functions/dsyncFetchPeople.md)

