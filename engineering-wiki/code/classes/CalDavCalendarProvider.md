# CalDavCalendarProvider

> Class in [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md), line 45

## Methods

| Method | What it does |
|---|---|
| [supports()](#supports) | CalDAV supports all-day events, checking a target calendar, and per-analyst accounts. |
| [serverUrl()](#serverurl) | The configured server, always ending in a slash. |
| [authMode()](#authmode) | Returns the configured HTTP auth mode - 'auto', 'digest' or 'basic' (anything else reads as 'auto'). |
| [cfg()](#cfg) | Request settings for `cardDavRequest()`, signed in as the current analyst. |
| [sameOrigin()](#sameorigin) | Same scheme, host and port. |
| [calendarUrl()](#calendarurl) | A calendar address, checked and normalised to end in a slash. |
| [eventUrl()](#eventurl) | The URL of one of our events. Refuses any id FreeITSM did not mint. |
| [xpath()](#xpath) | Parse a DAV XML body, or null. External entities are never loaded. |
| [text()](#text) | Returns the trimmed text of the first node an XPath query matches (optionally inside `$ctx`), or ''. |
| [buildCalendar()](#buildcalendar) | One whole VCALENDAR holding our event. No METHOD: RFC 4791 forbids it in a stored object. |
| [eventLines()](#eventlines) | BEGIN:VEVENT … END:VEVENT for the canonical event (see CalendarSyncProvider). |
| [unfold()](#unfold) | RFC 5545 unfolding: a line starting with a space or tab continues the one before. |
| [propName()](#propname) | The property name of a content line, upper-cased: "DTSTART;TZID=…:x" -> "DTSTART". |
| [rewriteEvent()](#rewriteevent) | Replace the lines FreeITSM owns in an event and keep everything else. |
| [createEvent()](#createevent) | PUTs a new event under a random 'freeitsm-...' name with If-None-Match, so nothing existing is overwritten; retries once on a name clash. |
| [updateEvent()](#updateevent) | Reads the event, replaces only the properties FreeITSM owns and PUTs it back with If-Match, rereading once if it changed meanwhile. |
| [deleteEvent()](#deleteevent) | Deletes the event; one already gone counts as success. |
| [pollChanges()](#pollchanges) | What changed in this calendar since last time. |
| [currentSyncToken()](#currentsynctoken) | The calendar's own sync-token right now, or '' when it does not say. |
| [pollChangesOnce()](#pollchangesonce) | One pass of `pollChanges()` - sync-collection when the server supports it, otherwise a comparison of our events' ETags - returning token, baseline, changed and removed. |
| [idFromHref()](#idfromhref) | The event id in an href, or null when the href is not one of ours. |
| [fetchChanges()](#fetchchanges) | Read the new times of changed events, in batches. |
| [eventTimes()](#eventtimes) | The start, end and all-day flag of the first VEVENT, as FreeITSM stores them: naive wall clock in `$tz`, all-day as 00:00 to 23:59:59 of the last day. |
| [parseDate()](#parsedate) | One DTSTART/DTEND line, converted to `$tz`. |
| [verifyConnection()](#verifyconnection) | Is the configured address a DAV server at all? |
| [verifyTarget()](#verifytarget) | True when the signed-in analyst can write events into this calendar (see `checkCalendar()`). |
| [checkCalendar()](#checkcalendar) | Can the signed-in analyst write events into this calendar? |
| [discoverCalendars()](#discovercalendars) | The calendars this sign-in can write events into. |
| [calendarPropfind()](#calendarpropfind) | The PROPFIND body asking a collection for its name, type, supported components and the analyst's privileges. |
| [describeCalendar()](#describecalendar) | What one PROPFIND response says about a collection. |
| [explain()](#explain) | One readable sentence for a failed request, without calling it a contacts problem. |

### supports

*public* - line 64

CalDAV supports all-day events, checking a target calendar, and per-analyst accounts.

```php
function supports(string $capability): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$capability` | `string` |  | a CalendarSyncProvider::CAP_* key - true for all_day, verify_target and per_analyst |

### serverUrl

*public* - line 76

The configured server, always ending in a slash.

```php
function serverUrl(): string
```

### authMode

*private* - line 83

Returns the configured HTTP auth mode - 'auto', 'digest' or 'basic' (anything else reads as 'auto').

```php
function authMode(): string
```

### cfg

*private* - line 96

Request settings for `cardDavRequest()`, signed in as the current analyst.

```php
function cfg(string $url, bool $interactive = true): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the absolute URL the request goes to, copied into the settings |
| `$interactive` | `bool` | `true` | short timeouts - true for anything that runs inside somebody's Save, false for the cron |

### sameOrigin

*public static* - line 121

Same scheme, host and port.

🔴 The guard that keeps a per-analyst calendar address from becoming a way
to make FreeITSM send authenticated requests to any host an analyst names
- the metadata service, an internal admin page. The address must sit on
the server an administrator configured.

```php
function sameOrigin(string $a, string $b): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` | `string` |  | the first absolute URL (untrusted - may be an analyst's saved address) |
| `$b` | `string` |  | the second absolute URL - a missing port is read as 443 for https, 80 otherwise |

**Called from:** [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)

### calendarUrl

*public* - line 140

A calendar address, checked and normalised to end in a slash.

```php
function calendarUrl(string $address): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$address` | `string` |  | the calendar address as saved under Preferences (untrusted); must be on the configured server |

### eventUrl

*private* - line 158

The URL of one of our events. Refuses any id FreeITSM did not mint.

```php
function eventUrl(string $calendarAddress, string $remoteEventId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's calendar address, checked by `calendarUrl()` |
| `$remoteEventId` | `string` |  | the event's 'freeitsm-<hex>' id; any other shape throws |

### xpath

*private static* - line 171

Parse a DAV XML body, or null. External entities are never loaded.

```php
function xpath(string $xml): ?DOMXPath
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$xml` | `string` |  | a response body from the DAV server; blank or unparseable gives null |

### text

*private static* - line 193

Returns the trimmed text of the first node an XPath query matches (optionally inside `$ctx`), or ''.

```php
function text(DOMXPath $xp, string $query, ?DOMNode $ctx = null): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$xp` | `DOMXPath` |  | the parsed document from `xpath()` |
| `$query` | `string` |  | an XPath query using the d: (DAV) and c: (CalDAV) prefixes |
| `$ctx` | `?DOMNode` | `null` | the node a relative query runs inside (one d:response); null searches the whole document |

### buildCalendar

*private* - line 207

One whole VCALENDAR holding our event. No METHOD: RFC 4791 forbids it in a stored object.

```php
function buildCalendar(string $uid, array $event): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$uid` | `string` |  | the 'freeitsm-<hex>' id, written as the iCalendar UID |
| `$event` | `array` |  | the canonical event shape (see CalendarSyncProvider) |

### eventLines

*private* - line 223

BEGIN:VEVENT … END:VEVENT for the canonical event (see CalendarSyncProvider).

```php
function eventLines(string $uid, array $event): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$uid` | `string` |  | the 'freeitsm-<hex>' id, written as the iCalendar UID |
| `$event` | `array` |  | the canonical event shape (see CalendarSyncProvider); 'timezone' defaults to PHP's zone |

### unfold

*public static* - line 245

RFC 5545 unfolding: a line starting with a space or tab continues the one before.

```php
function unfold(string $ics): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ics` | `string` |  | raw iCalendar text, CRLF or LF line endings |

### propName

*private static* - line 256

The property name of a content line, upper-cased: "DTSTART;TZID=…:x" -> "DTSTART".

```php
function propName(string $line): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$line` | `string` |  | one unfolded content line |

### rewriteEvent

*public static* - line 271

Replace the lines FreeITSM owns in an event and keep everything else.

Only the FIRST VEVENT is touched, and only its own properties - never
those of a VALARM inside it, which carry DESCRIPTION and SUMMARY of their
own. Returns null when there is no VEVENT to edit.

```php
function rewriteEvent(string $ics, array $newEventLines): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ics` | `string` |  | the event's calendar data as the server sent it back |
| `$newEventLines` | `array` |  | fresh VEVENT lines from `eventLines()`; their BEGIN, END and UID lines are ignored |

### createEvent

*public* - line 319

PUTs a new event under a random 'freeitsm-...' name with If-None-Match, so nothing existing is overwritten; retries once on a name clash.

```php
function createEvent(string $calendarAddress, array $event): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's calendar address, checked by `calendarUrl()` |
| `$event` | `array` |  | the canonical event shape (see CalendarSyncProvider) |

**Returns:** string  the event's uid, kept as the remote id

### updateEvent

*public* - line 348

Reads the event, replaces only the properties FreeITSM owns and PUTs it back with If-Match, rereading once if it changed meanwhile.

Throws CalendarEventMissing when the event was deleted from the calendar, so the caller can create a fresh one.

```php
function updateEvent(string $calendarAddress, string $remoteEventId, array $event): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's calendar address, checked by `calendarUrl()` |
| `$remoteEventId` | `string` |  | the 'freeitsm-<hex>' id `createEvent()` returned |
| `$event` | `array` |  | the canonical event shape (see CalendarSyncProvider) |

### deleteEvent

*public* - line 394

Deletes the event; one already gone counts as success.

```php
function deleteEvent(string $calendarAddress, string $remoteEventId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's calendar address, checked by `calendarUrl()` |
| `$remoteEventId` | `string` |  | the 'freeitsm-<hex>' id `createEvent()` returned |

### pollChanges

*public* - line 424

What changed in this calendar since last time.

🔑 sync-collection (RFC 6578), the standard way to ask a DAV server for
changes since a token. It lists hrefs and ETags only, so the events
FreeITSM did not create are never fetched, and ours are then read in
batches with a calendar-multiget.

Servers without sync-collection get the same answer a longer way: a list
of our events' ETags, remembered in the token and compared next time.

The token is prefixed with the method that produced it (`sync:` or
`etags:`), so one is never read as the other, and anything else - a
Microsoft delta link left over from before the provider was switched -
is simply re-baselined.

```php
function pollChanges(string $calendarAddress, ?string $token): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's calendar address, checked by `calendarUrl()` |
| `$token` | `?string` |  | the token the last poll returned ('sync:...' or 'etags:{json}'); null or '' takes a baseline |

### currentSyncToken

*private* - line 434

The calendar's own sync-token right now, or '' when it does not say.

```php
function currentSyncToken(string $calendar): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendar` | `string` |  | the calendar URL, already checked by `calendarUrl()` |

### pollChangesOnce

*private* - line 452

One pass of `pollChanges()` - sync-collection when the server supports it, otherwise a comparison of our events' ETags - returning token, baseline, changed and removed.

```php
function pollChangesOnce(string $calendarAddress, ?string $token, bool $retried): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's calendar address, checked by `calendarUrl()` |
| `$token` | `?string` |  | the token the last poll returned ('sync:...' or 'etags:{json}'); null or '' takes a baseline |
| `$retried` | `bool` |  | true on the second look after a suspicious "no changes" |

### idFromHref

*private static* - line 573

The event id in an href, or null when the href is not one of ours.

```php
function idFromHref(string $href): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$href` | `string` |  | a d:href from a multistatus response, absolute or server-relative |

### fetchChanges

*private* - line 588

Read the new times of changed events, in batches.

```php
function fetchChanges(string $calendar, array $hrefs): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendar` | `string` |  | the calendar URL, already checked by `calendarUrl()` |
| `$hrefs` | `array` |  | id => href as the server reported it |

**Returns:** array the 'changed' shape `pollChanges()` returns

### eventTimes

*public static* - line 626

The start, end and all-day flag of the first VEVENT, as FreeITSM stores them: naive wall clock in `$tz`, all-day as 00:00 to 23:59:59 of the last day.

```php
function eventTimes(string $ics, string $tz): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ics` | `string` |  | one event's calendar data as the server sent it |
| `$tz` | `string` |  | the PHP zone name the times are converted into (FreeITSM's own zone) |

**Returns:** array|null ['start'=>…, 'end'=>…, 'all_day'=>bool]

### parseDate

*private static* - line 700

One DTSTART/DTEND line, converted to `$tz`.

Three shapes are legal: UTC (a trailing Z), a named zone (TZID=…), and
"floating" (neither), which means local time wherever you are - read here
as FreeITSM's own zone. A date on its own (VALUE=DATE, or eight digits) is
an all-day value and is not converted, because a date has no zone.

⚠️ A zone name PHP does not know - Outlook writes Windows names such as
"GMT Standard Time" - is read as FreeITSM's zone rather than rejected. An
event that lands an hour out is visible and fixable; a change that is
silently dropped is neither.

```php
function parseDate(string $line, string $tz): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$line` | `string` |  | one unfolded DTSTART or DTEND line, parameters included |
| `$tz` | `string` |  | the PHP zone name to convert into; also how floating times and unknown TZIDs are read |

### verifyConnection

*public* - line 742

Is the configured address a DAV server at all?

Asked without signing in, because the connection holds nobody's password.
A DAV server answers an anonymous PROPFIND with 401 (it wants a sign-in)
or 207 (it lets anyone look); anything else is not a calendar server.

```php
function verifyConnection(): void
```

### verifyTarget

*public* - line 761

True when the signed-in analyst can write events into this calendar (see `checkCalendar()`).

```php
function verifyTarget(string $calendarAddress): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the calendar address to test, as the analyst chose it |

### checkCalendar

*public* - line 776

Can the signed-in analyst write events into this calendar?

```php
function checkCalendar(string $calendarAddress): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the calendar address to test, as the analyst chose it |

**Returns:** array ['ok'=>bool, 'name'=>string, 'error'=>string]

### discoverCalendars

*public* - line 805

The calendars this sign-in can write events into.

The standard route: the server address tells us who we are
(current-user-principal), that tells us where their calendars live
(calendar-home-set), and a Depth: 1 listing of that shows them. A server
that skips a step is tolerated: with no principal the address itself is
treated as the calendar home.

```php
function discoverCalendars(): array
```

**Returns:** array ['ok'=>bool, 'calendars'=>[['url','name']], 'auth'=>string, 'error'=>string]

### calendarPropfind

*private static* - line 861

The PROPFIND body asking a collection for its name, type, supported components and the analyst's privileges.

```php
function calendarPropfind(): string
```

### describeCalendar

*private static* - line 880

What one PROPFIND response says about a collection.

Missing properties are read generously: a server that does not report
its component set is assumed to hold events, and one that does not report
privileges is assumed writable - the write itself will say otherwise.

```php
function describeCalendar(DOMXPath $xp, DOMNode $resp, string $url): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$xp` | `DOMXPath` |  | the parsed multistatus document |
| `$resp` | `DOMNode` |  | one d:response element in it |
| `$url` | `string` |  | the collection's absolute URL - its last path segment is the name when no displayname is given |

### explain

*private* - line 913

One readable sentence for a failed request, without calling it a contacts problem.

```php
function explain(array $res): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$res` | `array` |  | the result of `cardDavRequest()` - 'status' and 'error' are read |

