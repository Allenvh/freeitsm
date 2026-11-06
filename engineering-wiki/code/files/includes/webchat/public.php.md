# includes/webchat/public.php

> PHP - 194 lines

Web chat — public request plumbing.

Required by the unauthenticated widget endpoints (api/webchat/{config,start,send,poll}).
These are the only FreeITSM endpoints a random website visitor reaches, so everything
that keeps them safe lives here: CORS scoped to the widget's own origin allowlist, the
widget-key lookup, per-conversation token loading, and basic rate limiting.

Trust model: the widget key only lets you START a conversation (and only from an allowed
origin). Reading or posting to an existing conversation needs its per-conversation TOKEN,
which lives in that one visitor's browser — so the public key leaking (it always does; it
ships in page source) never exposes anyone's chat.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [webchatGenerateToken()](/code/functions/webchatGenerateToken.md) | 19 | A fresh per-conversation capability token (browser-held). |
| [webchatClientIp()](/code/functions/webchatClientIp.md) | 25 | Best-effort client IP (rate limiting only — not a security boundary). |
| [webchatRequestOrigin()](/code/functions/webchatRequestOrigin.md) | 37 | The origin of the requesting page. Cross-origin requests carry an Origin header (the authoritative case in production, where the customer's site and the FreeITSM host are different origins). Same-origin requests — e.g. a demo site on the same host — omit Origin entirely, so we fall back to the scheme+host of the Referer. Returns '' if neither is present. Like the whole allowlist, this is a deterrent, not a crypto control. |
| [webchatPublicHeaders()](/code/functions/webchatPublicHeaders.md) | 64 | Emit CORS + JSON headers. Pass the request's origin to echo it back (required for the browser to accept the response on a cross-origin call); pass '*' when there is no origin (same-origin / curl). Call once the origin has been authorised. |
| [webchatHandlePreflight()](/code/functions/webchatHandlePreflight.md) | 77 | Answer a CORS preflight and exit. Call at the very top of each public endpoint. |
| [webchatFail()](/code/functions/webchatFail.md) | 94 | Emit a JSON error and stop. |
| [webchatPublicResolve()](/code/functions/webchatPublicResolve.md) | 108 | Load the widget for a public request, enforce active + origin allowlist, and set the CORS headers. Returns the widget row (joined to its channel). Exits with JSON on any failure — so the caller can treat the return as authorised. |
| [webchatLoadConversation()](/code/functions/webchatLoadConversation.md) | 137 | Load a conversation by its token, scoped to the widget's channel (so a token issued for one widget can't be replayed against another). Null if not found. |
| [webchatRateLimitStart()](/code/functions/webchatRateLimitStart.md) | 159 | Cap new conversations per IP per minute. Exits (429) on breach. |
| [webchatRateLimitSend()](/code/functions/webchatRateLimitSend.md) | 177 | Cap inbound messages per conversation per minute. Exits (429) on breach. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 144 | `webchatLoadConversation` | read [webchat_conversations](/code/tables/webchat_conversations.md) |  |
| 166 | `webchatRateLimitStart` | read [webchat_conversations](/code/tables/webchat_conversations.md) |  |
| 184 | `webchatRateLimitSend` | read [emails](/code/tables/emails.md) |  |

## How it hangs together

**Includes:** [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md)

**Included by:** [api/webchat/config.php](/code/files/api/webchat/config.php.md), [api/webchat/escalate.php](/code/files/api/webchat/escalate.php.md), [api/webchat/poll.php](/code/files/api/webchat/poll.php.md), [api/webchat/send.php](/code/files/api/webchat/send.php.md), [api/webchat/start.php](/code/files/api/webchat/start.php.md)

**Calls:** [webchatLoadByKey()](/code/functions/webchatLoadByKey.md), [webchatOriginAllowed()](/code/functions/webchatOriginAllowed.md), [webchatParseOrigins()](/code/functions/webchatParseOrigins.md)

