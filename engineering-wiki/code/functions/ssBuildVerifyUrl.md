# ssBuildVerifyUrl()

Absolute URL to the self-service email-verification page for a raw token.

```php
function ssBuildVerifyUrl(string $rawToken): string
```

Defined in [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), line 104.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawToken` | `string` |  | the unhashed verification token that goes in the link |

**Returns:** `string`

## Calls

[requestScheme()](/code/functions/requestScheme.md)

## Called from

- [api/self-service/register.php](/code/files/api/self-service/register.php.md)

