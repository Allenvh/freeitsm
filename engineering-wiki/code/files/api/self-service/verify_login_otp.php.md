# api/self-service/verify_login_otp.php

> API endpoint - 131 lines

API: Verify OTP during self-service login POST - Validates TOTP code for pending MFA login

## The endpoint

| | |
|---|---|
| URL | `api/self-service/verify_login_otp.php` |
| Protected by | Signed-in portal user (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/totp.php](/code/files/includes/totp.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [mfaThrottleMinutesRemaining()](/code/functions/mfaThrottleMinutesRemaining.md), [mfaThrottleRecordFailure()](/code/functions/mfaThrottleRecordFailure.md), [mfaThrottleReset()](/code/functions/mfaThrottleReset.md), [requesterIsActive()](/code/functions/requesterIsActive.md), [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md), [verifyTotpCode()](/code/functions/verifyTotpCode.md)

