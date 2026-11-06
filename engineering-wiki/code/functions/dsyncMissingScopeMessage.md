# dsyncMissingScopeMessage()

Say which branches are gone, and lead with the one that changes who gets in.

```php
function dsyncMissingScopeMessage(array $gone): string
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 252.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$gone` | `array` |  | missing branches as `dsyncMissingScopes()` returns them: [['dn' => ..., 'kind' => ...], ...] |

**Returns:** `string`

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)

