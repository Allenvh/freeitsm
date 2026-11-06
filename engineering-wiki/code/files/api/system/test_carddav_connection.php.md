# api/system/test_carddav_connection.php

> API endpoint - 161 lines

API: Test a CardDAV address book's settings. POST JSON { id?, carddav_url, carddav_username, carddav_password, carddav_auth }

Proves, in one PROPFIND, that we can reach the server, that the account can
sign in, and that the URL is a CardDAV collection — then lists the address
books it can see, which is the answer to the question an operator actually
has: *which* book should I point this at?

⭐ It also reports WHICH AUTHENTICATION SCHEME the server offered. That is
not trivia. A stock Baikal — the standard sabre/dav server — ships Digest,
and Basic against it is a flat 401 that reads as a wrong password. Naming
the scheme turns "authentication failed" into something actionable.

Like the LDAP test, this returns the REAL error rather than a sanitised one:
an admin debugging their own server needs the detail, and the endpoint is
already restricted to System admins.

A blank or masked password means "use the one already stored for provider
`id`", so Test works without re-typing the secret.

## The endpoint

| | |
|---|---|
| URL | `api/system/test_carddav_connection.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 57 |  | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/carddav.php](/code/files/includes/carddav.php.md)

**Calls:** [cardDavListAddressBooks()](/code/functions/cardDavListAddressBooks.md), [cardDavScanBook()](/code/functions/cardDavScanBook.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md)

