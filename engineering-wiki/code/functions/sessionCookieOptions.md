# sessionCookieOptions()

The attributes an authenticated session cookie must carry.

SameSite=Lax is doing the CSRF work here: it stops the cookie riding along with a
cross-site form post while leaving ordinary top-level navigation (a link in an
email to a ticket) working. Chrome already defaults to this; stating it explicitly
is what extends the same protection to Firefox and Safari.

```php
function sessionCookieOptions(): array
```

Defined in [includes/session_security.php](/code/files/includes/session_security.php.md), line 103.

**Returns:** `array`

## Calls

[requestIsHttps()](/code/functions/requestIsHttps.md)

## Called from

- [includes/session_security.php](/code/files/includes/session_security.php.md) - in [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md)

