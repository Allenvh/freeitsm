# oidcHttpGet()

GETs a JSON document (discovery, keys) and returns the body; throws on a network error or any status but 200.

```php
function oidcHttpGet(string $url): string
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 242.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the absolute URL to fetch |

**Returns:** `string`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [includes/oidc.php](/code/files/includes/oidc.php.md) - in [oidcDiscover()](/code/functions/oidcDiscover.md), [oidcValidateIdToken()](/code/functions/oidcValidateIdToken.md)

