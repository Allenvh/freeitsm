# api/myaccount/get_mfa_status.php

> API endpoint - 65 lines

API: Get MFA status for current user GET - Returns whether MFA is enabled Works for both analysts and self-service users via `getMfaAuthContext()`

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/get_mfa_status.php` |
| Protected by | Signed-in analyst (session check) (via includes/mfa_helpers.php) |
| Reads | `GET ctx` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/mfa_helpers.php](/code/files/includes/mfa_helpers.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getMfaAuthContext()](/code/functions/getMfaAuthContext.md)

