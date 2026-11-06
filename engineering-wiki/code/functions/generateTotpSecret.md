# generateTotpSecret()

Generate a random TOTP secret (Base32 encoded)

```php
function generateTotpSecret()
```

Defined in [includes/totp.php](/code/files/includes/totp.php.md), line 22.

**Returns:** string 32-character Base32 string

## Calls

[base32Encode()](/code/functions/base32Encode.md)

## Called from

- [api/myaccount/setup_mfa.php](/code/files/api/myaccount/setup_mfa.php.md)

