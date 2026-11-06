# api/system/browse_directory_ous.php

> API endpoint - 242 lines

API: the shape of the directory, for the OU browser.

POST { provider_id, ...live form values... }

Returns every organisational unit under the provider's base DN, as a flat
list of { dn, name, parent, people, total }, plus the root itself.

`people` is how many entries sit DIRECTLY in that OU and `total` is the whole
branch. Both matter: ticking Staff imports 24 people, and knowing that before
you run anything is the difference between choosing a scope and guessing at
one. A tree of names alone tells you the directory's shape but nothing about
what ticking a box would actually do.

The counts come from ONE paged search for people-DNs, rolled up in PHP —
not a search per OU, which on a directory of any size would be dozens of
round trips to render one screen.

Reads only. Administrators only.

## The endpoint

| | |
|---|---|
| URL | `api/system/browse_directory_ous.php` |
| Protected by | Signed-in analyst (session check) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ouBrowseParent()](/code/functions/ouBrowseParent.md) | 198 | The DN one level up. '' once there is nothing left. |
| [ouBrowseRdnValue()](/code/functions/ouBrowseRdnValue.md) | 212 | The readable half of a DN's first component: "OU=Staff,…" → "Staff". |
| [ouBrowseIsPlumbing()](/code/functions/ouBrowseIsPlumbing.md) | 230 | Active Directory's own furniture, which nobody is importing people from. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/directory_sync.php](/code/files/includes/directory_sync.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [dsyncScopes()](/code/functions/dsyncScopes.md), [ldapBindService()](/code/functions/ldapBindService.md), [ldapOpen()](/code/functions/ldapOpen.md)

