# api/self-service/register.php

> API endpoint - 134 lines

API: Self-Service Portal Registration POST - Creates a new user account or claims an existing passwordless account

## The endpoint

| | |
|---|---|
| URL | `api/self-service/register.php` |
| Protected by | Signed-in analyst (session check) (via includes/timezone.php); Signed-in analyst (session check) (via includes/csrf.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 57 |  | read [analysts](/code/tables/analysts.md) |  |
| 65 |  | read [users](/code/tables/users.md) |  |
| 105 |  | **delete** [user_verification_tokens](/code/tables/user_verification_tokens.md) |  |
| 107 |  | **insert** [user_verification_tokens](/code/tables/user_verification_tokens.md) (`email`, `password_hash`, `display_name`, `token_hash`, `expires_at`, `created_at`) |  |
| 120 |  | **delete** [user_verification_tokens](/code/tables/user_verification_tokens.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/self_service.php](/code/files/includes/self_service.php.md), [includes/users.php](/code/files/includes/users.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [selfServiceRegistrationEnabled()](/code/functions/selfServiceRegistrationEnabled.md), [ssBuildVerifyUrl()](/code/functions/ssBuildVerifyUrl.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md), [ssVerifyEmailBody()](/code/functions/ssVerifyEmailBody.md), [userSignsInElsewhere()](/code/functions/userSignsInElsewhere.md)

