# csrfOwnHosts()

This app's own host[:port], as the browser addresses it: the Host header, and - only when asked, because it costs a query - the configured public address (for a proxy that rewrites Host).

```php
function csrfOwnHosts(bool $withConfigured = false): array
```

Defined in [includes/csrf.php](/code/files/includes/csrf.php.md), line 155.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$withConfigured` | `bool` | `false` | true to add the host from the configured public URL (costs a database query) |

**Returns:** `array`

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md), [publicBaseUrlSetting()](/code/functions/publicBaseUrlSetting.md)

## Called from

- [includes/csrf.php](/code/files/includes/csrf.php.md) - in [csrfSameOrigin()](/code/functions/csrfSameOrigin.md)

