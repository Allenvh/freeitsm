# verifyTotpCode()

Verify a TOTP code against a secret

```php
function verifyTotpCode($base32Secret, $code, $window = 1)
```

Defined in [includes/totp.php](/code/files/includes/totp.php.md), line 65.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$base32Secret` |  |  | Base32-encoded secret |
| `$code` |  |  | 6-digit code to verify |
| `$window` |  | `1` | Number of time slices to check either side (default 1 = ±30s) |

**Returns:** bool Whether the code is valid

## Calls

[getTotpCode()](/code/functions/getTotpCode.md)

## Called from

- [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md)
- [api/myaccount/verify_mfa.php](/code/files/api/myaccount/verify_mfa.php.md)
- [api/self-service/verify_login_otp.php](/code/files/api/self-service/verify_login_otp.php.md)

