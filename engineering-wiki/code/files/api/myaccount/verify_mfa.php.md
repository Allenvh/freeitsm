# api/myaccount/verify_mfa.php

> API endpoint - 64 lines

API: Verify MFA setup POST - Verifies OTP code against pending secret, enables MFA if valid Works for both analysts and self-service users via `getMfaAuthContext()`

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/verify_mfa.php` |
| Protected by | Signed-in analyst (session check) (via includes/mfa_helpers.php) |
| Reads | `GET ctx` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/totp.php](/code/files/includes/totp.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/mfa_helpers.php](/code/files/includes/mfa_helpers.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [encryptValue()](/code/functions/encryptValue.md), [getMfaAuthContext()](/code/functions/getMfaAuthContext.md), [verifyTotpCode()](/code/functions/verifyTotpCode.md)

