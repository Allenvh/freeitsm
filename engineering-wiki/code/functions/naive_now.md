# naive_now()

🔴 "NOW" FOR A NAIVE WALL-CLOCK COLUMN — not for anything else.

Most datetimes in FreeITSM are UTC instants, and the way to ask the database
what time it is now is `UTC_TIMESTAMP()`. A few columns are deliberately NOT
instants: change windows, scheduled work and PIR actuals are stored as NAIVE
WALL CLOCKS, without a zone, so that "2pm" reads 2pm for everybody wherever
they are. See the three kinds of stored date in Timezones-and-Time-Handling.

⚠️ You cannot compare one of those against `UTC_TIMESTAMP()`. "Is this change
window open right now" asks whether a wall clock has passed, and the answer
has to be a wall clock too, or every window is judged an hour or two early.

Those queries used bare `NOW()`, which worked only by accident: MySQL evaluated
it in the DATABASE SERVER'S OS ZONE, which nothing declares and nothing
documents. Pinning the connection to UTC (GH #126, see config.php) removed that
accident, so the wall clock is now stated explicitly and comes from the
application's own configured zone — `date_default_timezone_set()` in config.php
— which is the zone every other "local" reading in the product already uses.
Where the database server and PHP agreed, which is the ordinary case, nothing
about these queries changes.

📌 OPEN QUESTION, deliberately not answered here: arguably this should be the
VIEWER's display zone rather than the installation's, since the naive value is
shown to them unconverted — an analyst in Vienna reads "14:00" and the dashboard
decides whether that is in progress using London's clock. Making it per-viewer
would mean two analysts seeing different counts on a shared dashboard, which is
its own problem. Left as it was rather than changed on the way past.

```php
function naive_now(): string
```

Defined in [includes/timezone.php](/code/files/includes/timezone.php.md), line 539.

**Returns:** `string`

## Called from

- [api/calendar/feed.php](/code/files/api/calendar/feed.php.md)
- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md) - in [enrolBackfillTickets()](/code/functions/enrolBackfillTickets.md)
- [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

