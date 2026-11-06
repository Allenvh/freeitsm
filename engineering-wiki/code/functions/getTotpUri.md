# getTotpUri()

Build the otpauth:// URI for QR code generation

```php
function getTotpUri($base32Secret, $accountName, $issuer = 'FreeITSM')
```

Defined in [includes/totp.php](/code/files/includes/totp.php.md), line 85.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$base32Secret` |  |  | Base32-encoded secret |
| `$accountName` |  |  | User identifier (e.g. username or email) |
| `$issuer` |  | `'FreeITSM'` | Application name |

**Returns:** string otpauth URI

## Called from

- [api/myaccount/setup_mfa.php](/code/files/api/myaccount/setup_mfa.php.md)

