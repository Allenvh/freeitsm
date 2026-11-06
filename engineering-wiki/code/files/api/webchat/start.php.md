# api/webchat/start.php

> API endpoint - 50 lines

PUBLIC endpoint: start a web chat conversation. Returns a per-conversation token the browser keeps and presents on every subsequent send/poll. No ticket is created yet — that happens on the first actual message (send.php), so an abandoned "opened the widget but said nothing" never litters the inbox.

## The endpoint

| | |
|---|---|
| URL | `api/webchat/start.php` |
| Protected by | Public - no sign-in check found (sign-in pages, webhooks and token links are meant to be; anything else, check the code) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | **insert** [webchat_conversations](/code/tables/webchat_conversations.md) (`channel_id`, `token`, `visitor_name`, `visitor_email`, `visitor_ip`, `created_datetime`, `last_activity_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/webchat/public.php](/code/files/includes/webchat/public.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [webchatClientIp()](/code/functions/webchatClientIp.md), [webchatFail()](/code/functions/webchatFail.md), [webchatGenerateToken()](/code/functions/webchatGenerateToken.md), [webchatHandlePreflight()](/code/functions/webchatHandlePreflight.md), [webchatPublicResolve()](/code/functions/webchatPublicResolve.md), [webchatRateLimitStart()](/code/functions/webchatRateLimitStart.md)

