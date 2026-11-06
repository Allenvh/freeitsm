# requestIsHttps()

Is this request actually over HTTPS?

X-Forwarded-Proto is only believed when the install says it is behind a trusted
proxy, because otherwise it is a header any client can set — and a client that
sets it would flag its own cookie Secure over a plain-HTTP connection and lock
itself out. Define TRUST_PROXY_HTTPS in config.php when terminating TLS upstream.

```php
function requestIsHttps(): bool
```

Defined in [includes/session_security.php](/code/files/includes/session_security.php.md), line 58.

**Returns:** `bool`

## Called from

- [api/calendar/get_feed_url.php](/code/files/api/calendar/get_feed_url.php.md)
- [api/tickets/get_schedule_feed_url.php](/code/files/api/tickets/get_schedule_feed_url.php.md)
- [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)
- [includes/landing.php](/code/files/includes/landing.php.md) - in [landingSetCookie()](/code/functions/landingSetCookie.md)
- [includes/public_url.php](/code/files/includes/public_url.php.md) - in [publicBaseUrl()](/code/functions/publicBaseUrl.md)
- [includes/session_security.php](/code/files/includes/session_security.php.md) - in [requestScheme()](/code/functions/requestScheme.md), [sessionCookieOptions()](/code/functions/sessionCookieOptions.md), [sessionCookieParamsAreHardened()](/code/functions/sessionCookieParamsAreHardened.md)

