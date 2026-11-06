# api/self-service/request_password_reset.php

> API endpoint - 123 lines

API: ask for a password-reset link for a SELF-SERVICE PORTAL account (GH #134).

POST JSON: { "email": "someone@example.com" }

── WHY THIS EXISTS ──────────────────────────────────────────────────────────

Reported by mbsouth: every customer and partner had been created in the ticket
system with a name and an email address and no password, and there was then no
way on earth for any of them to obtain one. The portal had a registration flow
and nothing else, so an account created FOR somebody was an account nobody
could ever sign in to. There was no administrator route either.

── THE RULES THIS FILE ENFORCES ─────────────────────────────────────────────

1. THE ANSWER IS ALWAYS THE SAME. Whether the address is unknown, known,
   disabled or signed in with SSO, the caller is told the identical thing.
   Anything else turns this endpoint into a way of asking "does this person
   have an account here?", which for a service desk's customer list is a
   question worth protecting.

2. THE TOKEN IS NEVER STORED. Only its SHA-256 is kept, so the table is not a
   list of working links if it is ever read.

3. ASKING AGAIN INVALIDATES THE LAST ONE. Two live links to one account means
   the older email keeps working after somebody has already used the newer.

4. AN SSO ACCOUNT IS NOT OFFERED A PASSWORD. Setting one would create a second
   way in that the organisation's identity provider does not know about, which
   is the opposite of why they turned SSO on.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/request_password_reset.php` |
| Protected by | Signed-in analyst (session check) (via includes/timezone.php); Signed-in analyst (session check) (via includes/csrf.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 |  | read [users](/code/tables/users.md) |  |
| 79 |  | **update** [user_password_reset_tokens](/code/tables/user_password_reset_tokens.md) (`used`) |  |
| 88 |  | **insert** [user_password_reset_tokens](/code/tables/user_password_reset_tokens.md) (`user_id`, `token_hash`, `expires_at`, `used`, `created_at`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/users.php](/code/files/includes/users.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ssBuildResetUrl()](/code/functions/ssBuildResetUrl.md), [ssResetEmailBody()](/code/functions/ssResetEmailBody.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md), [userSignsInElsewhere()](/code/functions/userSignsInElsewhere.md)

