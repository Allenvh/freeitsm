# sessionCookieParamsAreHardened()

Did the server's own configuration already give the session cookie the attributes we require? (i.e. did one of .user.ini / .htaccess / docker php.ini apply?)

```php
function sessionCookieParamsAreHardened(): bool
```

Defined in [includes/session_security.php](/code/files/includes/session_security.php.md), line 162.

**Returns:** `bool`

## Calls

[requestIsHttps()](/code/functions/requestIsHttps.md)

## Called from

- [includes/session_security.php](/code/files/includes/session_security.php.md) - in [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md)

