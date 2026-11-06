# api/contracts/delete_contact.php

> API endpoint - 41 lines

API: delete a supplier contact.

Fires the supplier_contact 'deleted' workflow event with the contact's name.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/delete_contact.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Input | id  the contact to delete (JSON body) |
| Output | {success} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [contacts](/code/tables/contacts.md) |  |
| 33 |  | **delete** [contacts](/code/tables/contacts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

