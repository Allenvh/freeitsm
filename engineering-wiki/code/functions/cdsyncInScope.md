# cdsyncInScope()

Is this mapped person inside the resolved scope?

```php
function cdsyncInScope(?array $resolved, array $p): bool
```

Defined in [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md), line 331.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$resolved` | `?array` |  | the result of `cdsyncResolveScope()`; null means everything is in scope |
| `$p` | `array` |  | one person as `cdsyncMapCard()` returns it - 'guid' and 'email' are read |

**Returns:** `bool`

## Called from

- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)

