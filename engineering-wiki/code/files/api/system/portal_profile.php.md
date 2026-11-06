# api/system/portal_profile.php

> API endpoint - 92 lines

API: what people may change about themselves in the self-service portal.

GET                                  the current settings, plus the address
                                     books the second one could apply to
POST { fields: [...], address_book } save them

🔴 Both settings decide what CUSTOMERS may write, and the second one lets
them write into the organisation's address book, so this is gated on System
administrators like every other System endpoint.

## The endpoint

| | |
|---|---|
| URL | `api/system/portal_profile.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [portalProfileState()](/code/functions/portalProfileState.md) | 22 | What the page needs to draw itself - always read back from the database. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 | `portalProfileState` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 78 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/users.php](/code/files/includes/users.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [portalProfileAddressBookWrites()](/code/functions/portalProfileAddressBookWrites.md), [portalProfileEditableFields()](/code/functions/portalProfileEditableFields.md)

