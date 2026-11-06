# api/webchat/config.php

> API endpoint - 41 lines

PUBLIC endpoint: return a widget's browser-facing config so widget.js can render. No authentication — a website visitor hits this. Guarded by the origin allowlist. Never returns anything sensitive (no key, no company internals) — just look & feel.

## The endpoint

| | |
|---|---|
| URL | `api/webchat/config.php` |
| Protected by | Public - no sign-in check found (sign-in pages, webhooks and token links are meant to be; anything else, check the code) |
| Reads | `GET key` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/webchat/public.php](/code/files/includes/webchat/public.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [webchatHandlePreflight()](/code/functions/webchatHandlePreflight.md), [webchatIsOpenNow()](/code/functions/webchatIsOpenNow.md), [webchatOfflineMessage()](/code/functions/webchatOfflineMessage.md), [webchatPublicResolve()](/code/functions/webchatPublicResolve.md)

