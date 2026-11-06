# d009Fetch()

Fetch a URL from this server. Returns [httpCode, transportError]. A transport error yields code 0, and every caller must treat that as "unknown", never as "blocked".

```php
function d009Fetch(string $url): array
```

Defined in [api/system/debug-tools/D009_guarded_paths.php](/code/files/api/system/debug-tools/D009_guarded_paths.php.md), line 100.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full address on this server to request; redirects are not followed |

**Returns:** `array`

## Called from

- [api/system/debug-tools/D009_guarded_paths.php](/code/files/api/system/debug-tools/D009_guarded_paths.php.md)

