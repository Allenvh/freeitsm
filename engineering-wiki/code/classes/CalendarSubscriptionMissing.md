# CalendarSubscriptionMissing

> Class in [includes/calendar_sync/CalendarSyncProvider.php](/code/files/includes/calendar_sync/CalendarSyncProvider.php.md), line 298

The subscription we tried to renew has already lapsed.

Its own class because the response is specific: create a fresh one, rather
than retrying a renewal against something that cannot come back.

