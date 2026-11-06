# getTotpCode()

Get the current TOTP code for a given secret

```php
function getTotpCode($base32Secret, $timeSlice = null)
```

Defined in [includes/totp.php](/code/files/includes/totp.php.md), line 32.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$base32Secret` |  |  | Base32-encoded secret |
| `$timeSlice` |  | `null` | Time slice (null = current) |

**Returns:** string 6-digit zero-padded code

## Calls

[base32Decode()](/code/functions/base32Decode.md)

## Called from

- [includes/totp.php](/code/files/includes/totp.php.md) - in [verifyTotpCode()](/code/functions/verifyTotpCode.md)

