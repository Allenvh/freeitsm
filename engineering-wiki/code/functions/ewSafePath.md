# ewSafePath()

A page path from a request, made safe, or null. Accepts "handbook/start", "/handbook/start.md", "code/files/api/x.php" (a dot is fine inside a name).

```php
function ewSafePath(string $p): ?string
```

Defined in [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md), line 36.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `string` |  | the page path as received - untrusted request input |

**Returns:** `?string`

## Called from

- [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md) - in [ewResolveLink()](/code/functions/ewResolveLink.md), [ewListDir()](/code/functions/ewListDir.md), [ewSearch()](/code/functions/ewSearch.md)

