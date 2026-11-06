# csrfHasSessionCookie()

Did this request arrive with a session cookie at all? Without one there is nothing to forge.

```php
function csrfHasSessionCookie(): bool
```

Defined in [includes/csrf.php](/code/files/includes/csrf.php.md), line 94.

**Returns:** `bool`

## Called from

- [includes/csrf.php](/code/files/includes/csrf.php.md) - in [csrfEnforce()](/code/functions/csrfEnforce.md)

