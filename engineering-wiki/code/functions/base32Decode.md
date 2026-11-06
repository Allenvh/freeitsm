# base32Decode()

Decode Base32-encoded string to binary (RFC 4648)

```php
function base32Decode($encoded)
```

Defined in [includes/totp.php](/code/files/includes/totp.php.md), line 131.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$encoded` |  |  | Base32-encoded string |

**Returns:** string Raw binary data

## Called from

- [includes/totp.php](/code/files/includes/totp.php.md) - in [getTotpCode()](/code/functions/getTotpCode.md)

