# csrfEnforce()

The central check. Runs on include of request_guard.php (so on every request that loads functions.php), after the endpoint has started its session.

```php
function csrfEnforce(): void
```

Defined in [includes/csrf.php](/code/files/includes/csrf.php.md), line 239.

**Returns:** `void`

## Calls

[csrfHasSessionCookie()](/code/functions/csrfHasSessionCookie.md), [csrfIsExempt()](/code/functions/csrfIsExempt.md), [csrfIsStateChanging()](/code/functions/csrfIsStateChanging.md), [csrfProvidedToken()](/code/functions/csrfProvidedToken.md), [csrfRefuse()](/code/functions/csrfRefuse.md), [csrfRequestPath()](/code/functions/csrfRequestPath.md), [csrfSameOrigin()](/code/functions/csrfSameOrigin.md), [csrfSessionIsAuthenticated()](/code/functions/csrfSessionIsAuthenticated.md)

## Called from

- [includes/request_guard.php](/code/files/includes/request_guard.php.md)

