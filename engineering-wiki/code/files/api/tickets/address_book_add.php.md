# api/tickets/address_book_add.php

> API endpoint - 81 lines

API: add a person to a CardDAV address book (#133).

GET  ?user_id=N              which address books they could be added to, and
                               what the new card would carry
  POST {user_id, provider_id}  create the card and link the person to it

Offered only for somebody linked to no source, with an email address, and
only address books whose administrator switched on BOTH write-back and
"let analysts add people". See includes/carddav_create.php for the checks
made on the way (duplicates, and that the next import will see the card).

Same gate as the people editors that show the button: the Tickets module,
and a person this analyst can reach.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/address_book_add.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/carddav_create.php](/code/files/includes/carddav_create.php.md)

**Calls:** [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [cardDavCreateContact()](/code/functions/cardDavCreateContact.md), [cardDavCreatePreview()](/code/functions/cardDavCreatePreview.md), [cardDavCreateTargets()](/code/functions/cardDavCreateTargets.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

