# api/system/test_carddav_write.php

> API endpoint - 110 lines

API: Can this account WRITE to the chosen address book? POST JSON { id?, carddav_url, carddav_username, carddav_password, carddav_auth, carddav_addressbook }

🔑 THIS ASKS THE SERVER. FreeITSM keeps no list of "CardDAV servers we support
writing to", and should not: writing is a plain `PUT`, defined by RFC 6352
alongside the `PROPFIND` and `REPORT` the import already uses, so any server
the import works against can in principle be written to. What genuinely varies
is PERMISSION — a shared, subscribed or published address book is very
commonly read-only, and the alternative to asking up front is finding out by
failing in the middle of somebody's edit.

🔴 IT WRITES NOTHING. The check is a `PROPFIND` for
`current-user-privilege-set`, which reports what the account is allowed to do.
Testing by writing a card and deleting it again would be a far worse idea than
it sounds: the delete can fail, and the operator's address book is left with a
contact called something like "FreeITSM test".

A blank or masked password means "use the one already stored for provider
`id`", so this works without re-typing the secret — same rule as the
connection test beside it.

## The endpoint

| | |
|---|---|
| URL | `api/system/test_carddav_write.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/carddav_write.php](/code/files/includes/carddav_write.php.md)

**Calls:** [cardDavCanWrite()](/code/functions/cardDavCanWrite.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md)

