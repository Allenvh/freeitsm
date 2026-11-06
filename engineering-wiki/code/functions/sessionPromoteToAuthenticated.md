# sessionPromoteToAuthenticated()

Call at every point that turns an anonymous session into an identified one, and whenever a password changes.

Rotates the session ID (destroying the old one, so a fixated ID is worthless) and
re-issues the cookie with the hardened attributes.

Safe to call unconditionally: it does nothing if there is no active session, and
it will not fatal if output has already started — it logs instead, because
refusing to log somebody in over a cookie-attribute problem would be a worse
outcome than the one being prevented.

```php
function sessionPromoteToAuthenticated(bool $newIdentity = true): void
```

Defined in [includes/session_security.php](/code/files/includes/session_security.php.md), line 130.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$newIdentity` | `bool` | `true` | true at sign-in (the CSRF token is rotated too); false after a password change by the same person |

**Returns:** `void`

## Calls

[csrfRotate()](/code/functions/csrfRotate.md), [sessionCookieOptions()](/code/functions/sessionCookieOptions.md), [sessionCookieParamsAreHardened()](/code/functions/sessionCookieParamsAreHardened.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) - in [completeSelfServiceSso()](/code/functions/completeSelfServiceSso.md)
- [api/myaccount/change_password.php](/code/files/api/myaccount/change_password.php.md)
- [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md)
- [api/self-service/change_password.php](/code/files/api/self-service/change_password.php.md)
- [api/self-service/login.php](/code/files/api/self-service/login.php.md)
- [api/self-service/verify_login_otp.php](/code/files/api/self-service/verify_login_otp.php.md)
- [self-service/verify-email.php](/code/files/self-service/verify-email.php.md)

