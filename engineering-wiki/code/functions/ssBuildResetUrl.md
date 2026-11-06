# ssBuildResetUrl()

Absolute URL to the self-service password-reset page for a raw token.

Built exactly as `ssBuildVerifyUrl()` does rather than from BASE_URL: these two
links are sent by the same code to the same people, and a reset that lands on
a different host from the confirmation is the sort of thing that gets read as
a phishing attempt.

```php
function ssBuildResetUrl(string $rawToken): string
```

Defined in [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), line 123.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawToken` | `string` |  | the unhashed password-reset token that goes in the link |

**Returns:** `string`

## Calls

[requestScheme()](/code/functions/requestScheme.md)

## Called from

- [api/self-service/request_password_reset.php](/code/files/api/self-service/request_password_reset.php.md)

