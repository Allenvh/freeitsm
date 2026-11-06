# landingRefreshCookieFromPreference()

Re-issue the cookie from the analyst's stored preference, on login.

This is what makes the preference follow the person rather than the browser:
set it on your desktop, and the first time you log in on your laptop the
cookie is written there too. It also self-heals if somebody clears cookies.

Silent on failure — a preference lookup must never be the reason a login
fails. The cost of it not running is that somebody lands on the install
default once more.

```php
function landingRefreshCookieFromPreference(PDO $conn, int $analystId): void
```

Defined in [includes/landing.php](/code/files/includes/landing.php.md), line 144.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 151 | `landingRefreshCookieFromPreference` | read [user_preferences](/code/tables/user_preferences.md) |  |

## Calls

[landingIsValid()](/code/functions/landingIsValid.md), [landingSetCookie()](/code/functions/landingSetCookie.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)
- [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md)

