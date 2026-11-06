# oidcIsAbsoluteHttpUrl()

True only for an absolute http:// or https:// URL with a host part.

Deliberately stricter than filter_var(..., FILTER_VALIDATE_URL), which
happily accepts schemes we must never redirect a browser to (javascript:,
data:, mailto:) and which some PHP builds accept without a host.

```php
function oidcIsAbsoluteHttpUrl(string $url): bool
```

Defined in [includes/oidc.php](/code/files/includes/oidc.php.md), line 129.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the URL to check, e.g. an endpoint from a discovery document |

**Returns:** `bool`

## Called from

- [includes/oidc.php](/code/files/includes/oidc.php.md) - in [oidcDiscover()](/code/functions/oidcDiscover.md)

