# api/myaccount/disable_mfa.php

> API endpoint - 55 lines

API: Disable MFA POST - Verifies password and disables MFA Works for both analysts and self-service users via `getMfaAuthContext()`

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/disable_mfa.php` |
| Protected by | Signed-in analyst (session check) (via includes/mfa_helpers.php) |
| Reads | `GET ctx` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/mfa_helpers.php](/code/files/includes/mfa_helpers.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getMfaAuthContext()](/code/functions/getMfaAuthContext.md)

