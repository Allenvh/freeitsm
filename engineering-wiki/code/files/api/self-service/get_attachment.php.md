# api/self-service/get_attachment.php

> API endpoint - 124 lines

Serve an email attachment to the requester who owns the ticket.

The portal could show a thread but never hand back a FILE, so when an analyst
emailed someone a form, a driver or a screenshot, the one place that person
was told to look couldn't give it to them.

This is the portal twin of api/tickets/get_attachment.php. The difference is
the authorisation model: the analyst version asks "can this analyst reach the
ticket's company", this one asks "did this requester raise this ticket". Both
resolve the ticket THROUGH the attachment's email rather than trusting anything
in the URL, so ids can't be enumerated into someone else's files.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_attachment.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md), read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/portal_visibility.php](/code/files/includes/portal_visibility.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/managers.php](/code/files/includes/managers.php.md)

**Calls:** [attachmentSendHeaders()](/code/functions/attachmentSendHeaders.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [portalEmailVisibility()](/code/functions/portalEmailVisibility.md), [portalThirdPartyPolicy()](/code/functions/portalThirdPartyPolicy.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md)

