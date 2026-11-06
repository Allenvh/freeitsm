# ssResetEmailBody()

HTML body for the password-reset email.

⚠️ Deliberately says "set" rather than "reset", because for most of the people
who will receive it this is the FIRST password they have ever had: a contact
created by the service desk has no password at all, and telling them to
"reset" one they never set reads as a mistake or a scam (GH #134).

```php
function ssResetEmailBody(string $displayName, string $link, int $validHours = 1): string
```

Defined in [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), line 144.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$displayName` | `string` |  | the recipient's name for the greeting; '' greets them as 'there' |
| `$link` | `string` |  | the reset link from `ssBuildResetUrl()` |
| `$validHours` | `int` | `1` | how long the link lasts, in hours, as stated in the email |

**Returns:** `string`

## Called from

- [api/self-service/request_password_reset.php](/code/files/api/self-service/request_password_reset.php.md)

