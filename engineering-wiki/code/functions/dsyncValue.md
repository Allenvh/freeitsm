# dsyncValue()

One attribute off a raw entry, as a plain string ('' when absent).

```php
function dsyncValue(array $entry, string $attr): string
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 363.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$entry` | `array` |  | one raw entry from `ldap_get_entries()` (attribute keys lower-cased) |
| `$attr` | `string` |  | the attribute name, any case; '' gives '' |

**Returns:** `string`

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncMapPerson()](/code/functions/dsyncMapPerson.md)

