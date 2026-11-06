# includes/mfa_helpers.php

> PHP - 47 lines

MFA Helper - Shared authentication context for both analysts and self-service users. Used by api/myaccount/ MFA endpoints to support both user types without duplication.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [getMfaAuthContext()](/code/functions/getMfaAuthContext.md) | 15 | Determine which user type is authenticated and return their context. When both analyst and user sessions coexist, `$preferredType` selects which to use. Returns null if no valid session exists. |

## How it hangs together

**Included by:** [api/myaccount/disable_mfa.php](/code/files/api/myaccount/disable_mfa.php.md), [api/myaccount/get_mfa_status.php](/code/files/api/myaccount/get_mfa_status.php.md), [api/myaccount/setup_mfa.php](/code/files/api/myaccount/setup_mfa.php.md), [api/myaccount/verify_mfa.php](/code/files/api/myaccount/verify_mfa.php.md)

