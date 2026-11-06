# api/self-service/reset_password.php

> API endpoint - 133 lines

API: use a reset link to set a SELF-SERVICE PORTAL password (GH #134).

POST JSON: { "token": "<raw token>", "password": "...", "confirm_password": "..." }

The second half of api/self-service/request_password_reset.php — read the rules
at the top of that file first.

── WHAT MAKES A TOKEN ACCEPTABLE ────────────────────────────────────────────

All four conditions are checked in ONE statement, so there is no window between
deciding a token is good and using it, and no chance of the four drifting apart
later:

  - the hash exists
  - it has not been used
  - it has not expired  (UTC_TIMESTAMP, because every stored instant is UTC)
  - the account it points at is still active and still local

⚠️ THE ACCOUNT IS RE-CHECKED HERE, not just when the link was sent. An hour is
long enough for somebody to be deactivated or moved to SSO, and a link minted
before that must not still work after it.

⚠️ ONE MESSAGE FOR EVERY BAD TOKEN. Wrong, already used, expired and
belonging-to-a-closed-account are indistinguishable in the reply. Saying
"expired" rather than "not found" would confirm that a token was once real.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/reset_password.php` |
| Protected by | Public - no sign-in check found (sign-in pages, webhooks and token links are meant to be; anything else, check the code) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 73 |  | read [user_password_reset_tokens](/code/tables/user_password_reset_tokens.md), read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 100 |  | **update** [user_password_reset_tokens](/code/tables/user_password_reset_tokens.md) (`used`) |  |
| 108 |  | **update** [users](/code/tables/users.md) (`password_hash`) |  |
| 113 |  | **update** [user_password_reset_tokens](/code/tables/user_password_reset_tokens.md) (`used`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

