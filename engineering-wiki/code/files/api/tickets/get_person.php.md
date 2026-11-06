# api/tickets/get_person.php

> API endpoint - 77 lines

API: one person (a `users` row - a requester, not an analyst), complete, for the shared person editor (includes/person_editor.php). GET ?id=<users.id>

WHY IT EXISTS
-------------
Tickets -> Users and Assets -> Users each had their own editor, fed from their
own list endpoint (get_users.php and api/assets/get_people.php), and the two
lists are different shapes. One editor needs one source of truth for the
record it is about to change, rather than trusting whichever list the page it
happens to be on has loaded - so it reads the person here.

Reached from BOTH modules, so either grants it; the subject check is the real
guard. A refusal reads exactly like an id that does not exist.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_person.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 68 |  | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/users.php](/code/files/includes/users.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md)

**Calls:** [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [costCentreSelectSql()](/code/functions/costCentreSelectSql.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md), [userDirectoryOwnedFields()](/code/functions/userDirectoryOwnedFields.md)

