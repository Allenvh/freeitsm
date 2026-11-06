# base32Encode()

Encode binary data to Base32 (RFC 4648)

```php
function base32Encode($data)
```

Defined in [includes/totp.php](/code/files/includes/totp.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$data` |  |  | Raw binary data |

**Returns:** string Base32-encoded string

## Called from

- [includes/totp.php](/code/files/includes/totp.php.md) - in [generateTotpSecret()](/code/functions/generateTotpSecret.md)

