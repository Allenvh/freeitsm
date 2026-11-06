# includes/calendar_sync/CalendarSyncProvider.php

> PHP - 299 lines

CalendarSyncProvider — the provider-agnostic contract for putting an analyst's scheduled work into the calendar they actually live in (GH discussion #75).

Everything above this line — which tickets sync, whose, what happens when one
is reassigned or unscheduled, the remote-id map, the per-analyst enrolment and
the settings UI — talks to this interface and never knows which calendar is
live. That is the same split as includes/integrations/IssueTrackerProvider.php
and includes/messaging/MessagingProvider.php, and it is deliberately copied
rather than reinvented.

🔑 THE REASON THIS IS AN INTERFACE AND NOT JUST GRAPH CODE. What varies
between providers is genuinely small: create, update and delete an event in a
named person's calendar. Everything difficult here — deciding whose calendar,
removing the old copy when a ticket moves from A to B, not orphaning events
when somebody opts out — is provider-independent. So keeping the door open for
Google (or CalDAV, or nothing at all) costs almost nothing, while a
Microsoft-shaped hole would have to be dug out again later.

⚠️ Not every install can use ANY of these. A shop with no supported provider
falls back to a subscribe (.ics) link, which needs no provider code at all —
so "we have not implemented your calendar" must never mean "you get nothing".

Concrete providers are constructed with a DECRYPTED calendar_connections row
(credentials already a PHP array), exactly as IssueTrackerProvider is.

── The canonical event shape (what `push()` is given) ────────────────────────

  [
    'subject'   => 'TICKET-000105 — Rebuild laptop',   // required
    'body'      => 'Requester: …\nStatus: …',          // plain text, may be ''
    'start'     => '2026-09-01 14:00:00',   // NAIVE wall clock — see below
    'end'       => '2026-09-01 16:00:00',
    'all_day'   => false,
    'timezone'  => 'Europe/London',         // the zone the naive values mean
    'url'       => 'https://…/tickets/?ticket_id=635',  // link back
  ]

⚠️ START AND END ARE NAIVE WALL-CLOCK, not UTC. Scheduling values in FreeITSM
are stored without a zone on purpose — "2pm" means 2pm to every analyst (see
assets/js/schedule.js). A provider MUST combine them with 'timezone' rather
than assuming UTC, or every event lands an hour out for half the year. This is
the single most likely way to get this integration subtly wrong.

## Classes

- [CalendarSyncProvider](/code/classes/CalendarSyncProvider.md) (class, line 47) - CalendarSyncProvider — the provider-agnostic contract for putting an analyst's scheduled work into the calendar they actually live in (GH discussion #75).
- [CalendarEventMissing](/code/classes/CalendarEventMissing.md) (class, line 290) - The event we were told to update is no longer there.
- [CalendarSubscriptionMissing](/code/classes/CalendarSubscriptionMissing.md) (class, line 298) - The subscription we tried to renew has already lapsed.

## How it hangs together

**Included by:** [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md), [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), [includes/calendar_sync/MicrosoftCalendarProvider.php](/code/files/includes/calendar_sync/MicrosoftCalendarProvider.php.md)

