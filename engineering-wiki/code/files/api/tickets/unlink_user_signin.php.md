# api/tickets/unlink_user_signin.php

> API endpoint - 92 lines

API Endpoint: Unlink a portal user from their sign-in provider

POST JSON { id }

Removes both halves of the link: `users.auth_provider_id` (which provider the
email-first router sends them to, and which one strict isolation lets in) and
their `user_sso_identities` rows (the provider's id for them). Afterwards they
are back where a first-time requester starts: their next sign-in through a
provider links them again by verified email, and a password works if they
have one. Tickets and details are untouched.

Refused for a person a directory import keeps up to date (`is_managed`): the
next sync would re-attach them, so unlinking here would only appear to work.
Written to system_logs as 'signin_unlinked'.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/unlink_user_signin.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | read [user_sso_identities](/code/tables/user_sso_identities.md), read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 73 |  | **delete** [user_sso_identities](/code/tables/user_sso_identities.md) |  |
| 75 |  | **update** [users](/code/tables/users.md) (`auth_provider_id`) |  |
| 76 |  | **insert** [system_logs](/code/tables/system_logs.md) (`log_type`, `analyst_id`, `details`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

