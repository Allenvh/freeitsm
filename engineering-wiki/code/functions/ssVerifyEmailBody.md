# ssVerifyEmailBody()

HTML body for the verification email.

```php
function ssVerifyEmailBody(string $displayName, string $link): string
```

Defined in [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), line 166.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$displayName` | `string` |  | the recipient's name for the greeting; '' greets them as 'there' |
| `$link` | `string` |  | the verification link from `ssBuildVerifyUrl()` |

**Returns:** `string`

## Called from

- [api/self-service/register.php](/code/files/api/self-service/register.php.md)

