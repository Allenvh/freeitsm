# api/myaccount/verify_login_otp.php

> API endpoint - 252 lines

API: Verify OTP during login POST - Verifies TOTP code for MFA login challenge, completes login if valid

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/verify_login_otp.php` |
| Protected by | Signed-in analyst (session check) (via includes/csrf.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [analysts](/code/tables/analysts.md) |  |
| 41 |  | read [analysts](/code/tables/analysts.md) |  |
| 172 |  | **update** [analysts](/code/tables/analysts.md) (`last_login_datetime`) |  |
| 178 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 189 |  | **insert** [trusted_devices](/code/tables/trusted_devices.md) (`analyst_id`, `device_token_hash`, `user_agent`, `ip_address`, `created_datetime`, `expires_datetime`) |  |
| 196 |  | **delete** [trusted_devices](/code/tables/trusted_devices.md) |  |
| 211 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 238 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/totp.php](/code/files/includes/totp.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/mfa_throttle.php](/code/files/includes/mfa_throttle.php.md), [includes/landing.php](/code/files/includes/landing.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [landingRefreshCookieFromPreference()](/code/functions/landingRefreshCookieFromPreference.md), [mfaThrottleMinutesRemaining()](/code/functions/mfaThrottleMinutesRemaining.md), [mfaThrottleRecordFailure()](/code/functions/mfaThrottleRecordFailure.md), [mfaThrottleReset()](/code/functions/mfaThrottleReset.md), [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md), [verifyTotpCode()](/code/functions/verifyTotpCode.md)

