# api/system/test_directory_mapping.php

> API endpoint - 171 lines

API: show what the field mapping would actually produce.

POST { provider_id, ...the same field values the form holds... }

Fetches ONE real person from the directory and runs the configured mapping
over them, so each row can show the value it would import rather than just
the attribute name it was told to read.

An attribute name is abstract. "employeeID → NW-1011" is not. Most mapping
mistakes are invisible until somebody notices a column is empty weeks later —
this turns that into something you can see before you save.

Also returns every attribute the sample person HAS, so a field that cannot be
mapped because the directory simply does not carry it is distinguishable from
one that is mapped to the wrong name.

Reads only. Administrators only.

## The endpoint

| | |
|---|---|
| URL | `api/system/test_directory_mapping.php` |
| Protected by | Signed-in analyst (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/directory_sync.php](/code/files/includes/directory_sync.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [dsyncAttr()](/code/functions/dsyncAttr.md), [dsyncMapPerson()](/code/functions/dsyncMapPerson.md), [ldapBindService()](/code/functions/ldapBindService.md), [ldapOpen()](/code/functions/ldapOpen.md)

