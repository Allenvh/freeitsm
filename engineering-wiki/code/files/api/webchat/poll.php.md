# api/webchat/poll.php

> API endpoint - 90 lines

PUBLIC endpoint: fetch new messages in a conversation since a given id. Returns both directions (so a reload rebuilds the full transcript) — inbound tagged 'visitor', analyst replies tagged 'agent'. Needs the conversation token. The widget calls this on a short interval; it's the delivery mechanism for analyst replies (v1 = polling).

## The endpoint

| | |
|---|---|
| URL | `api/webchat/poll.php` |
| Protected by | Public - no sign-in check found (sign-in pages, webhooks and token links are meant to be; anything else, check the code) |
| Reads | `GET key`, `GET token`, `GET after` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 |  | read [emails](/code/tables/emails.md) |  |
| 75 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/webchat/public.php](/code/files/includes/webchat/public.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [webchatFail()](/code/functions/webchatFail.md), [webchatGetMessages()](/code/functions/webchatGetMessages.md), [webchatHandlePreflight()](/code/functions/webchatHandlePreflight.md), [webchatLoadConversation()](/code/functions/webchatLoadConversation.md), [webchatMirrorAgentReplies()](/code/functions/webchatMirrorAgentReplies.md), [webchatPublicResolve()](/code/functions/webchatPublicResolve.md)

