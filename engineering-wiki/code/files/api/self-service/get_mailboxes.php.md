# api/self-service/get_mailboxes.php

> API endpoint - 33 lines

API: Get the mailboxes this self-service user may raise a ticket through GET - Returns mailbox id, name, and email address only

Their own company's plus shared ones on a multi-company install (GH #172) -
see includes/self_service_mailboxes.php.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_mailboxes.php` |
| Protected by | Signed-in portal user (session check) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/self_service_mailboxes.php](/code/files/includes/self_service_mailboxes.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [selfServiceMailboxes()](/code/functions/selfServiceMailboxes.md)

