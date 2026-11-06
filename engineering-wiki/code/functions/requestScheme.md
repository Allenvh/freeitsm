# requestScheme()

'https' or 'http' — the scheme to put on an absolute URL built from this request.

⚠️ Use this, never a bare `$_SERVER['HTTPS']` test. Behind a proxy that terminates
TLS (nginx, Caddy, Traefik, an AWS ALB) Apache only ever sees plain HTTP, so the
bare test says 'http' and every link built from it is wrong. For an OIDC redirect
URI that is fatal rather than cosmetic: Entra and friends refuse a non-TLS redirect
URI outright (GH #152).

```php
function requestScheme(): string
```

Defined in [includes/session_security.php](/code/files/includes/session_security.php.md), line 90.

**Returns:** `string`

## Calls

[requestIsHttps()](/code/functions/requestIsHttps.md)

## Called from

- [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md)
- [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md)
- [api/system/debug-tools/D009_guarded_paths.php](/code/files/api/system/debug-tools/D009_guarded_paths.php.md)
- [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncEventFromTask()](/code/functions/calendarSyncEventFromTask.md), [calendarSyncEventFromTicket()](/code/functions/calendarSyncEventFromTicket.md)
- [includes/csat.php](/code/files/includes/csat.php.md) - in [csatBuildUrl()](/code/functions/csatBuildUrl.md)
- [includes/oidc.php](/code/files/includes/oidc.php.md) - in [oidcRedirectUri()](/code/functions/oidcRedirectUri.md)
- [includes/self_service_email.php](/code/files/includes/self_service_email.php.md) - in [ssBuildVerifyUrl()](/code/functions/ssBuildVerifyUrl.md), [ssBuildResetUrl()](/code/functions/ssBuildResetUrl.md)
- [self-service/logout.php](/code/files/self-service/logout.php.md)
- [system/api/docs.php](/code/files/system/api/docs.php.md)
- [system/api/index.php](/code/files/system/api/index.php.md)
- [system/help/sso.php](/code/files/system/help/sso.php.md)
- [system/sso/index.php](/code/files/system/sso/index.php.md)
- [system/webhooks/index.php](/code/files/system/webhooks/index.php.md)

