# api/myaccount/setup_mfa.php

> API endpoint - 45 lines

API: Begin MFA setup POST - Generates TOTP secret, stores in session pending verification Returns secret and otpauth URI for QR code generation Works for both analysts and self-service users via `getMfaAuthContext()`

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/setup_mfa.php` |
| Protected by | Signed-in analyst (session check) (via includes/mfa_helpers.php) |
| Reads | `GET ctx` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/totp.php](/code/files/includes/totp.php.md), [includes/mfa_helpers.php](/code/files/includes/mfa_helpers.php.md)

**Calls:** [generateTotpSecret()](/code/functions/generateTotpSecret.md), [getMfaAuthContext()](/code/functions/getMfaAuthContext.md), [getTotpUri()](/code/functions/getTotpUri.md)

