# self-service/verify-email.php

> PHP - 119 lines

Self-service email verification landing page.

Opened from the confirmation link in a registration email. Validates the
token, then applies the parked password to the user (creating the users row
if this was a brand-new email), signs them in, and sends them to the portal.
This is the ONLY place a self-service password gets set from registration.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [user_verification_tokens](/code/tables/user_verification_tokens.md) |  |
| 40 |  | read [users](/code/tables/users.md) |  |
| 61 |  | **update** [users](/code/tables/users.md) (`password_hash`, `display_name`) |  |
| 65 |  | **update** [users](/code/tables/users.md) (`tenant_id`) |  |
| 69 |  | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `password_hash`, `tenant_id`, `created_at`) |  |
| 75 |  | **delete** [user_verification_tokens](/code/tables/user_verification_tokens.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/users.php](/code/files/includes/users.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md), [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md), [userSignsInElsewhere()](/code/functions/userSignsInElsewhere.md)

