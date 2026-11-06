# api/self-service/change_password.php

> API endpoint - 80 lines

API: Change password for self-service user POST - Verifies current password and updates to new one

## The endpoint

| | |
|---|---|
| URL | `api/self-service/change_password.php` |
| Protected by | Signed-in portal user (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 |  | read [users](/code/tables/users.md) |  |
| 69 |  | **update** [users](/code/tables/users.md) (`password_hash`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md)

