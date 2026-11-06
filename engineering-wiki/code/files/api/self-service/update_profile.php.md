# api/self-service/update_profile.php

> API endpoint - 174 lines

API: Update self-service user profile

POST - preferred name, plus the contact details a person may maintain about
themselves: the ones an administrator allows on System → Portal profile
(portalProfileEditableFields(), never wider than USER_SELF_EDITABLE_FIELDS).

🔴 THIS ENDPOINT IS REACHED BY A CUSTOMER, NOT AN ANALYST. It writes to
`users`, the same table the analyst screens and directory sync write, so the
field list is taken from the server and NEVER from the request body. A
portal user posting {"manager_id":…} or {"employee_id":…} has those keys
ignored, not honoured — see includes/users.php for why those three are out.

🔑 A contact from an address book, where the administrator allows it, has
their change SENT TO THE ADDRESS BOOK FIRST and saved here only once it was
accepted. The analyst screens do it the other way round (save, then report
the push), and that is right for them: an analyst is told, and can act. A
customer cannot, and a local save the address book refused would be reverted
by the next import - an edit that silently vanishes overnight, which is the
one outcome this feature exists to prevent.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/update_profile.php` |
| Protected by | Signed-in portal user (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 148 |  | **update** [users](/code/tables/users.md) |  |
| 159 |  | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/users.php](/code/files/includes/users.php.md), [includes/carddav_write.php](/code/files/includes/carddav_write.php.md)

**Calls:** [cardDavPushPersonChanges()](/code/functions/cardDavPushPersonChanges.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [portalProfileAccess()](/code/functions/portalProfileAccess.md), [userPersonFieldValue()](/code/functions/userPersonFieldValue.md)

