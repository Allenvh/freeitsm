# CalendarEventMissing

> Class in [includes/calendar_sync/CalendarSyncProvider.php](/code/files/includes/calendar_sync/CalendarSyncProvider.php.md), line 290

The event we were told to update is no longer there.

Its own class because the caller's response is specific and correct: create a
replacement and re-point the map row. Someone deleting a FreeITSM event from
their own calendar is ordinary behaviour.

