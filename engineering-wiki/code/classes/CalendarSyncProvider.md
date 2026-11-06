# CalendarSyncProvider

> Class in [includes/calendar_sync/CalendarSyncProvider.php](/code/files/includes/calendar_sync/CalendarSyncProvider.php.md), line 47

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

## Methods

| Method | What it does |
|---|---|
| [withAccount()](#withaccount) | Act as this analyst. Returns `$this` so it chains off the factory. |
| [__construct()](#__construct) | Keeps the decrypted calendar_connections row (credentials already an array) the provider works from. |
| [getProvider()](#getprovider) | Returns the connection's provider key (for example 'microsoft' or 'caldav'), or ''. |
| [supports()](#supports) | Does this provider do X? |
| [createEvent()](#createevent) | Create an event in `$calendarAddress`'s calendar. Returns the provider's id for it, which the caller stores in calendar_sync_events. |
| [updateEvent()](#updateevent) | Update an event in place. |
| [deleteEvent()](#deleteevent) | Remove an event. |
| [verifyConnection()](#verifyconnection) | Prove the connection works at all: credentials valid, permission granted. |
| [pollChanges()](#pollchanges) | What has changed in this calendar since last time (GH #75, bi-directional). |
| [createSubscription()](#createsubscription) | Ask the provider to tell us when this calendar changes. |
| [renewSubscription()](#renewsubscription) | Push an existing subscription's expiry out. Same return as create. |
| [deleteSubscription()](#deletesubscription) | Stop notifications. An ALREADY-GONE subscription is a success: the desired end state is "not subscribed", and that is satisfied. |
| [verifyTarget()](#verifytarget) | Does this address have a calendar we can write to? |

### withAccount

*public* - line 81

Act as this analyst. Returns `$this` so it chains off the factory.

```php
function withAccount(array $account): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$account` | `array` |  | the analyst's decrypted calendar_enrolments.credentials; [] for a provider that needs none |

### __construct

*public* - line 105

Keeps the decrypted calendar_connections row (credentials already an array) the provider works from.

```php
function __construct(array $connection)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$connection` | `array` |  | the decrypted calendar_connections row, credentials already a PHP array |

### getProvider

*public* - line 111

Returns the connection's provider key (for example 'microsoft' or 'caldav'), or ''.

```php
function getProvider(): string
```

### supports

*public* - line 125

Does this provider do X?

The settings screen needs to know BEFORE it renders a field — discovering
it from a thrown exception is the wrong way round. Same reasoning as
IssueTrackerProvider::supports().

```php
function supports(string $capability): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$capability` | `string` |  | a CAP_* key, e.g. CalendarSyncProvider::CAP_ALL_DAY |

### createEvent

*public* - line 144

Create an event in `$calendarAddress`'s calendar. Returns the provider's id for it, which the caller stores in calendar_sync_events.

🔴 THE RETURNED ID IS LOAD-BEARING. Without it there is no way to update
or remove the event later, and a reassigned ticket leaves a copy in the
old analyst's calendar with nothing pointing at it. A provider that cannot
return a durable id cannot be supported.

```php
function createEvent(string $calendarAddress, array $event): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | whose calendar - a mailbox address for Microsoft, a calendar URL for CalDAV |
| `$event` | `array` |  | the canonical event shape described at the top of this file |

### updateEvent

*public* - line 158

Update an event in place.

Implementations SHOULD treat "the event is gone" as a recoverable state
and throw CalendarEventMissing, so the caller can create a fresh one
rather than the analyst silently losing the entry — somebody deleting it
from their own calendar is a normal thing to do, not an error.

```php
function updateEvent(string $calendarAddress, string $remoteEventId, array $event): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | whose calendar - a mailbox address for Microsoft, a calendar URL for CalDAV |
| `$remoteEventId` | `string` |  | the provider's id for the event, as `createEvent()` returned it |
| `$event` | `array` |  | the canonical event shape described at the top of this file |

### deleteEvent

*public* - line 170

Remove an event.

An event that is ALREADY gone is a success, not a failure: the desired
end state is "not in their calendar", and that is satisfied. Treating it
as an error would leave the map row behind and retry forever.

```php
function deleteEvent(string $calendarAddress, string $remoteEventId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | whose calendar - a mailbox address for Microsoft, a calendar URL for CalDAV |
| `$remoteEventId` | `string` |  | the provider's id for the event, as `createEvent()` returned it |

### verifyConnection

*public* - line 183

Prove the connection works at all: credentials valid, permission granted.

Separate from `verifyTarget()` ON PURPOSE. This answers "can FreeITSM talk to
the provider", that one answers "does this person have a calendar" — they
fail for entirely different reasons and need entirely different fixes, so
a settings screen must be able to report them separately rather than
collapsing both into "it didn't work".

Throws with a human-readable message on failure; returns nothing on success.

```php
function verifyConnection(): void
```

### pollChanges

*public* - line 213

What has changed in this calendar since last time (GH #75, bi-directional).

]
🔴 'baseline' IS THE SAFETY RAIL AND IT IS NOT OPTIONAL. A provider that
has lost its place — an expired token, a moved mailbox, a revoked
permission — answers with everything, or with nothing. Code that reads
"absent" as "deleted" would then unschedule an entire service desk on the
strength of a token expiring. A caller must treat baseline as "I know
nothing yet", never as "it is all gone".
Times come back as NAIVE wall clock in the caller's zone, matching the way
FreeITSM stores them.

```php
function pollChanges(string $calendarAddress, ?string $token): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | whose calendar - a mailbox address for Microsoft, a calendar URL for CalDAV |
| `$token` | `?string` |  | opaque provider state from the previous call; null means "no history — take a baseline" |

**Returns:** array [ 'token'    => string|null,   // store this for next time 'baseline' => bool,          // TRUE when there was no usable history and // this call established one. The caller MUST // apply NOTHING on a baseline. 'changed'  => [ ['remote_event_id'=>…, 'start'=>'Y-m-d H:i:s', 'end'=>…, 'all_day'=>bool], … ], 'removed'  => [ 'remote_event_id', … ],

### createSubscription

*public* - line 236

Ask the provider to tell us when this calendar changes.

⚠️ AN ACCELERATOR, NEVER THE ONLY MECHANISM. A notification says only that
something changed, not what — the caller still runs `pollChanges()`. And
notifications go missing: the provider drops them, the endpoint is down for
a deploy, a subscription lapses unnoticed. A silent gap then looks exactly
like "nothing changed", so the poll must remain as a backstop.

```php
function createSubscription(string $calendarAddress, string $notifyUrl, string $secret): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | whose calendar - a mailbox address for Microsoft, a calendar URL for CalDAV |
| `$notifyUrl` | `string` |  | publicly reachable HTTPS endpoint |
| `$secret` | `string` |  | echoed back in every notification; the only thing telling a real callback from anyone on the internet POSTing to a deliberately public URL |

**Returns:** array ['id' => string, 'expires' => 'Y-m-d H:i:s']

### renewSubscription

*public* - line 246

Push an existing subscription's expiry out. Same return as create.

```php
function renewSubscription(string $subscriptionId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$subscriptionId` | `string` |  | the provider's subscription id, as `createSubscription()` returned it |

### deleteSubscription

*public* - line 257

Stop notifications. An ALREADY-GONE subscription is a success: the desired end state is "not subscribed", and that is satisfied.

```php
function deleteSubscription(string $subscriptionId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$subscriptionId` | `string` |  | the provider's subscription id, as `createSubscription()` returned it |

### verifyTarget

*public* - line 277

Does this address have a calendar we can write to?

Drives the honest error on the analyst's own settings — an analyst whose
FreeITSM email is not their mailbox UPN must be told "we could not find a
mailbox for you", not left with a switch that appears on and never works.

Providers that cannot answer should report CAP_VERIFY_TARGET as false;
the default assumes the address is fine rather than blocking enrolment on
a check the provider cannot perform.

```php
function verifyTarget(string $calendarAddress): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the address to check - a mailbox address for Microsoft, a calendar URL for CalDAV |

