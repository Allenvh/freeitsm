# api/myaccount/change_password.php

> API endpoint - 87 lines

API: Change own password POST - Validates current password and updates to new password

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/change_password.php` |
| Protected by | Signed-in analyst (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [analysts](/code/tables/analysts.md) |  |
| 55 |  | **update** [analysts](/code/tables/analysts.md) (`password_hash`, `last_modified_datetime`, `password_changed_datetime`) |  |
| 60 |  | **update** [analysts](/code/tables/analysts.md) (`password_hash`, `last_modified_datetime`) |  |
| 73 |  | **update** [analysts](/code/tables/analysts.md) (`must_change_password`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md)

