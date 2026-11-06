# oidcHttpPost()

POSTs form fields to an OIDC endpoint and returns the body whatever the status, so the caller can read an error reply.

Throws only on a network error.

```php
function oidcHttpPost(string $url, array $fields): string
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 270.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the absolute endpoint URL, e.g. the token endpoint |
| `$fields` | `array` |  | the form fields to send, URL-encoded as the body |

**Returns:** `string`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [includes/oidc.php](/code/files/includes/oidc.php.md) - in [oidcExchangeCode()](/code/functions/oidcExchangeCode.md)

