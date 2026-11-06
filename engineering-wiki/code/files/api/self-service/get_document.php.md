# api/self-service/get_document.php

> API endpoint - 122 lines

Serve a file attached to a SHARED ticket note, to the requester who raised that ticket.

Notes could carry files from the moment note attachments arrived (discussion
#69), but only INTERNAL notes could: a shared note had to drop them, because
the portal had no way to hand a document back and offering one would have been
a promise to the requester that was never kept. That limitation is what this
endpoint removes (discussion #103).

This is the portal twin of api/documents/download.php, exactly as
api/self-service/get_attachment.php is the portal twin of the analyst-side
attachment route. The difference is the authorisation model: the analyst
version asks "may this analyst reach this document", this one asks "did this
requester raise the ticket the document hangs off, and is the note it hangs
off one they are allowed to read".

─────────────────────────────────────────────────────────────────────────────
 THE RULE THAT MAKES THIS SAFE
─────────────────────────────────────────────────────────────────────────────
Both halves are ONE join, not two checks:

    documents -> document_links (parent_type='ticket_note')
              -> ticket_notes  (is_internal = 0)
              -> tickets       (user_id = this requester)

An internal note's files are therefore unreachable BY CONSTRUCTION rather than
by a separate condition somebody could later forget, reorder, or short-circuit.
A miss is a 404 whatever the reason, so a wrong id and somebody else's id are
indistinguishable from outside and ids cannot be enumerated into another
customer's files.

Only `parent_type = 'ticket_note'` is served. Documents hanging off assets,
contracts, changes or anything else are NOT portal material and must not
become reachable because they happen to share a table.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_document.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 64 |  | read [documents](/code/tables/documents.md), read [document_links](/code/tables/document_links.md), read [ticket_notes](/code/tables/ticket_notes.md), read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/managers.php](/code/files/includes/managers.php.md)

**Calls:** [attachmentSendHeaders()](/code/functions/attachmentSendHeaders.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [documentStoragePath()](/code/functions/documentStoragePath.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md)

