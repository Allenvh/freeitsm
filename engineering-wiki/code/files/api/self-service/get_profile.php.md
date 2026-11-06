# api/self-service/get_profile.php

> API endpoint - 64 lines

API: Get self-service user profile

GET - display name, email, preferred name, and the contact details a person
may maintain about themselves: the ones an administrator has allowed on
System → Portal profile (portalProfileEditableFields()), which ones a
directory owns, and whether a change is sent to an address book.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_profile.php` |
| Protected by | Signed-in portal user (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/users.php](/code/files/includes/users.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [portalProfileAccess()](/code/functions/portalProfileAccess.md)

