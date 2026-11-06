# includes/subscribe_modal.php

> PHP - 85 lines

The "add this calendar to your phone" dialogue — shared markup.

Used by the Calendar module (the shared team calendar) and by Preferences
(an analyst's own scheduled work, GH #75). They hand out different feeds but
the act is identical: here is a URL, here is a QR code, here is how to add it
on iOS and Android, and here is how to revoke it.

🔑 LABELS ARE PASSED IN RATHER THAN LOOKED UP HERE. The two callers live in
different translation namespaces (calendar.* and system.*), and moving the
strings into a shared namespace would have silently untranslated them in every
locale that had already translated the originals. The markup is shared; the
words stay where they are.

⚠️ Behaviour lives in assets/js/subscribe.js — load it, and qrcode.min.js.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [renderSubscribeModal()](/code/functions/renderSubscribeModal.md) | 28 | Prints the "subscribe to this calendar feed" dialogue - feed address, QR code and phone instructions - with the labels given. |

## How it hangs together

**Included by:** [calendar/index.php](/code/files/calendar/index.php.md), [system/preferences/index.php](/code/files/system/preferences/index.php.md)

