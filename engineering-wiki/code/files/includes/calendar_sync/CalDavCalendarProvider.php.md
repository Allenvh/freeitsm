# includes/calendar_sync/CalDavCalendarProvider.php

> PHP - 925 lines

CalDavCalendarProvider — scheduled work into an analyst's calendar on any standard CalDAV server: Baikal, Nextcloud, Radicale, SOGo, Fastmail, iCloud.

Asked for in https://github.com/edmozley/freeitsm/issues/133 by an operator
running his own sabre/dav server. The Microsoft route (GH #75) already did
this for Exchange; this is the same feature without the vendor.

──────────────────────────────────────────────────────────────────────────
🔑 IT SIGNS IN AS THE ANALYST. Microsoft lets one administrator-approved app
write into every mailbox, so that provider is built from the connection
alone. A CalDAV server has no equivalent - each calendar belongs to its
owner's account - so every call here runs with the credentials the analyst
saved under Preferences, handed over through `withAccount()`. The connection
row holds only the server address and the auth scheme.

That changes who may choose the calendar, too. With Microsoft an analyst
could never set their own address, because the app could write to anybody's.
Here the analyst's own sign-in is the limit, so choosing their own calendar
is safe - with one guard: the calendar must be on the configured server
(sameOrigin()), so a saved address cannot turn FreeITSM into a tool for
sending requests to anywhere else on the network.

🔴 NEVER REGENERATE AN EVENT. An analyst may add a reminder, a note or a
colour to one of these in their own client. `updateEvent()` therefore edits
the lines FreeITSM owns in the calendar data the server sent back and leaves
every other byte alone - the same rule the CardDAV write-back follows.

🔴 AUTH IS NEGOTIATED. A stock Baikal wants Digest and answers Basic with a
401 that looks exactly like a wrong password. The transport is the CardDAV
one (cardDavRequest), which negotiates for exactly that reason.
──────────────────────────────────────────────────────────────────────────

Event identity: FreeITSM names every event it creates `freeitsm-<hex>`, used
both as the iCalendar UID and as the resource name (`freeitsm-<hex>.ics`).
That is the remote_event_id. It is also how the poll tells our events from
the analyst's own appointments without fetching any of theirs.

## Classes

- [CalDavCalendarProvider](/code/classes/CalDavCalendarProvider.md) (class, line 45)

## How it hangs together

**Includes:** [includes/calendar_sync/CalendarSyncProvider.php](/code/files/includes/calendar_sync/CalendarSyncProvider.php.md), [includes/carddav.php](/code/files/includes/carddav.php.md), [includes/ics.php](/code/files/includes/ics.php.md)

**Included by:** [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md), [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md)

**Calls:** [cardDavAbsoluteUrl()](/code/functions/cardDavAbsoluteUrl.md), [cardDavRequest()](/code/functions/cardDavRequest.md), [icsEvent()](/code/functions/icsEvent.md), [icsFold()](/code/functions/icsFold.md)

