# api/contracts/save_contact.php

> API endpoint - 64 lines

API: create or update a supplier contact.

Fires the supplier_contact 'created' or 'updated' workflow event.

## The endpoint

| | |
|---|---|
| URL | `api/contracts/save_contact.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Input | id           the contact to update - omit to create one |
| Input | supplier_id  the supplier the contact works for (optional) |
| Input | first_name   required |
| Input | surname      required |
| Input | email, mobile, job_title, direct_dial, switchboard  optional contact details |
| Input | is_active    1 or 0 (default 1) |
| Output | {success, id} |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 |  | **update** [contacts](/code/tables/contacts.md) (`supplier_id`, `first_name`, `surname`, `email`, `mobile`, `job_title`, `direct_dial`, `switchboard`, `is_active`) |  |
| 52 |  | **insert** [contacts](/code/tables/contacts.md) (`supplier_id`, `first_name`, `surname`, `email`, `mobile`, `job_title`, `direct_dial`, `switchboard`, `is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

