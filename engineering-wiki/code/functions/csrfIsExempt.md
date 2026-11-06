# csrfIsExempt()

True when the path is on CSRF_EXEMPT_PATHS - an exact file, or anything under an entry ending in '/'.

```php
function csrfIsExempt(string $path): bool
```

Defined in [includes/csrf.php](/code/files/includes/csrf.php.md), line 78.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | the request path relative to the app root, as `csrfRequestPath()` gives it |

**Returns:** `bool`

## Called from

- [includes/csrf.php](/code/files/includes/csrf.php.md) - in [csrfEnforce()](/code/functions/csrfEnforce.md)

