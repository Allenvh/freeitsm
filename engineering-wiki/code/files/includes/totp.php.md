# includes/totp.php

> PHP - 154 lines

TOTP (Time-based One-Time Password) Implementation RFC 6238 / RFC 4226 compliant

Pure PHP implementation — no external dependencies.
Uses HMAC-SHA1 with 30-second time steps and 6-digit codes.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [generateTotpSecret()](/code/functions/generateTotpSecret.md) | 22 | Generate a random TOTP secret (Base32 encoded) |
| [getTotpCode()](/code/functions/getTotpCode.md) | 32 | Get the current TOTP code for a given secret |
| [verifyTotpCode()](/code/functions/verifyTotpCode.md) | 65 | Verify a TOTP code against a secret |
| [getTotpUri()](/code/functions/getTotpUri.md) | 85 | Build the otpauth:// URI for QR code generation |
| [base32Encode()](/code/functions/base32Encode.md) | 103 | Encode binary data to Base32 (RFC 4648) |
| [base32Decode()](/code/functions/base32Decode.md) | 131 | Decode Base32-encoded string to binary (RFC 4648) |

## How it hangs together

**Included by:** [api/myaccount/setup_mfa.php](/code/files/api/myaccount/setup_mfa.php.md), [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md), [api/myaccount/verify_mfa.php](/code/files/api/myaccount/verify_mfa.php.md), [api/self-service/verify_login_otp.php](/code/files/api/self-service/verify_login_otp.php.md)

