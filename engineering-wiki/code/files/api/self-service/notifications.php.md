# api/self-service/notifications.php

> API endpoint - 67 lines

API: the signed-in PORTAL user's notifications - the portal's bell (discussion #62: a manager hears about a new ticket from their team).

GET  ?action=list[&count_only=1]          same shape as api/notifications/get_notifications.php
  POST ?action=mark_read  {ids:[..]}|{all}   same shape as api/notifications/mark_read.php
  POST ?action=clear      {ids:[..]}|{all, include_unread}   same as api/notifications/clear.php

The same shapes on purpose: the bell (assets/js/notification-bell.js) is one
script for both, told only which URLs to call. And the same service,
NotificationsService, handed a portal user rather than an analyst - so the
coalescing, the "unread survives Clear all" catch and the scoping (somebody
else's ids simply match nothing) are the analyst bell's, not a copy of them.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/notifications.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET action`, `GET count_only` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/notifications.php](/code/files/includes/services/notifications.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

