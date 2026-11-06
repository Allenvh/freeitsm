# includes/notification_bell.php

> PHP - 39 lines

The notification bell's markup - one bell for the analyst app and the self-service portal (discussions #55 and #62). The look is assets/css/notification-bell.css and the behaviour assets/js/notification-bell.js; each header prints this and calls NotificationBell.init() with its own URLs.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [notificationBellRender()](/code/functions/notificationBellRender.md) | 15 | Print the bell: its stylesheet, markup and script, and start it. |

## How it hangs together

**Included by:** [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md), [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)

**Calls:** [t()](/code/functions/t.md)

