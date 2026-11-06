# includes/password_gate.php

> PHP - 89 lines

Make "you must change your password" actually mean it.

⚠️ WHY THIS EXISTS. Both the password-expiry policy and the new
must_change_password flag worked by redirecting to force_password_change.php at
the end of login. That is a signpost, not a gate: the session is fully
authenticated by then, so typing /tickets/ into the address bar walked straight
past it. Verified — an account flagged to change its password reached the inbox
with a 200.

For the expiry policy that was merely weak. For the seeded admin/freeitsm account
it would have made the whole fix cosmetic, which is the one outcome worth avoiding
when the finding is "the default credentials are permanent".

So the flag is enforced on every request instead of only at the moment of sign-in.
Included from functions.php and runs on include, for the same reason
request_guard.php does: there is no other choke point every page passes through.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [enforcePasswordChangeGate()](/code/functions/enforcePasswordChangeGate.md) | 32 | Stop an analyst who owes a password change from doing anything else. |

## How it hangs together

**Included by:** [includes/functions.php](/code/files/includes/functions.php.md)

