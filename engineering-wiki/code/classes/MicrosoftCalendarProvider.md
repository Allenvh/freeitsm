# MicrosoftCalendarProvider

> Class in [includes/calendar_sync/MicrosoftCalendarProvider.php](/code/files/includes/calendar_sync/MicrosoftCalendarProvider.php.md), line 28

## Methods

| Method | What it does |
|---|---|
| [supports()](#supports) | Microsoft Graph supports all-day events, HTML bodies, checking a mailbox exists, and change notifications. |
| [token()](#token) | An app-only access token, cached on the connection until it expires. |
| [verifyConnection()](#verifyconnection) | Mint a token. If that succeeds the credentials are right and Calendars.ReadWrite was granted and consented; nothing else can be true and this fail. |
| [call()](#call) | One Graph call. Returns [httpCode, decodedBody]. |
| [toGraphEvent()](#tographevent) | Translate our canonical event into Graph's shape. |
| [createEvent()](#createevent) | Creates the event in the mailbox's calendar through Graph and returns Graph's event id. |
| [updateEvent()](#updateevent) | PATCHes the event through Graph; throws CalendarEventMissing on a 404 so the caller can create a fresh one. |
| [deleteEvent()](#deleteevent) | Deletes the event through Graph; a 404 counts as success. |
| [pollChanges()](#pollchanges) | Read changes back out of the mailbox with a Graph delta query. |
| [toLocal()](#tolocal) | Graph's {dateTime,timeZone} to a naive wall clock in `$tz`. |
| [callAbsolute()](#callabsolute) | A GET against a URL Graph gave us (nextLink / deltaLink are absolute). |
| [createSubscription()](#createsubscription) | Subscribes to updates and deletions in the mailbox's calendar, posting to `$notifyUrl` with `$secret` as clientState, for SUBSCRIPTION_MINUTES. |
| [renewSubscription()](#renewsubscription) | Extends a Graph subscription by SUBSCRIPTION_MINUTES; throws CalendarSubscriptionMissing when it has already lapsed. |
| [deleteSubscription()](#deletesubscription) | Removes a Graph subscription; one already gone counts as success. |
| [expiryToLocal()](#expirytolocal) | Graph's UTC expiry to the naive local datetime the column stores. |
| [verifyTarget()](#verifytarget) | True when Graph can read the mailbox's calendar - i.e. the address exists and the app can reach it. |
| [briefly()](#briefly) | Graph error bodies are long and carry request ids. Keep enough to diagnose without putting a wall of JSON in last_error, which is 500 characters and is shown to a human. |

### supports

*public* - line 40

Microsoft Graph supports all-day events, HTML bodies, checking a mailbox exists, and change notifications.

```php
function supports(string $capability): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$capability` | `string` |  | a CalendarSyncProvider::CAP_* key |

### token

*private* - line 59

An app-only access token, cached on the connection until it expires.

App-only tokens carry no refresh token — when one expires we simply ask
for another. The 300-second margin is so a token that is valid when we
check cannot expire mid-request.

```php
function token(): string
```

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 105 | `MicrosoftCalendarProvider::token` | **update** [calendar_connections](/code/tables/calendar_connections.md) (`token_data`) |  |

### verifyConnection

*public* - line 124

Mint a token. If that succeeds the credentials are right and Calendars.ReadWrite was granted and consented; nothing else can be true and this fail.

```php
function verifyConnection(): void
```

### call

*private* - line 138

One Graph call. Returns [httpCode, decodedBody].

```php
function call(string $method, string $path, ?array $body = null): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$method` | `string` |  | the HTTP method - GET, POST, PATCH or DELETE |
| `$path` | `string` |  | the Graph path after /v1.0, e.g. '/users/{address}/events', already URL-encoded where needed |
| `$body` | `?array` | `null` | the JSON body to send; null sends none |

### toGraphEvent

*private* - line 174

Translate our canonical event into Graph's shape.

⚠️ TWO THINGS GRAPH IS STRICT ABOUT, and both are easy to get wrong:

1. dateTime must carry NO zone suffix, with the zone named separately in
   timeZone. Our stored values are naive wall-clock precisely so that "2pm"
   means 2pm — appending a Z would reinterpret every one of them as UTC and
   land the event an hour out for half the year.

2. isAllDay REQUIRES both ends at midnight and the end EXCLUSIVE — the day
   after the last day. We store all-day as 00:00–23:59:59 so that readers
   ignoring the flag still see a sensible block, so it has to be converted
   here. Sending 23:59:59 with isAllDay:true is rejected outright.

```php
function toGraphEvent(array $event): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `array` |  | the canonical event shape (see CalendarSyncProvider) - naive start/end plus timezone |

### createEvent

*public* - line 213

Creates the event in the mailbox's calendar through Graph and returns Graph's event id.

```php
function createEvent(string $calendarAddress, array $event): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's mailbox address (UPN), as Graph knows it |
| `$event` | `array` |  | the canonical event shape (see CalendarSyncProvider) |

### updateEvent

*public* - line 231

PATCHes the event through Graph; throws CalendarEventMissing on a 404 so the caller can create a fresh one.

```php
function updateEvent(string $calendarAddress, string $remoteEventId, array $event): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's mailbox address (UPN), as Graph knows it |
| `$remoteEventId` | `string` |  | Graph's event id, as `createEvent()` returned it |
| `$event` | `array` |  | the canonical event shape (see CalendarSyncProvider) |

### deleteEvent

*public* - line 255

Deletes the event through Graph; a 404 counts as success.

```php
function deleteEvent(string $calendarAddress, string $remoteEventId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's mailbox address (UPN), as Graph knows it |
| `$remoteEventId` | `string` |  | Graph's event id, as `createEvent()` returned it |

### pollChanges

*public* - line 289

Read changes back out of the mailbox with a Graph delta query.

🔑 DELTA, NOT SUBSCRIPTIONS. Change notifications need a publicly
reachable HTTPS endpoint Microsoft can call, plus renewal before it
expires — impossible for the many FreeITSM installs that are internal
only. A delta query is an ordinary outbound GET on a cron: same
information, no inbound firewall hole.

⚠️ calendarView/delta needs a WINDOW, and it is the expanded view. The
window is deliberately narrow — recent past to a few months ahead — since
a wider one makes the first sync enormous for no benefit; nobody edits
last year's appointments.

```php
function pollChanges(string $calendarAddress, ?string $token): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's mailbox address (UPN), as Graph knows it |
| `$token` | `?string` |  | the full deltaLink the last poll returned; null or '' takes a baseline |

### toLocal

*private* - line 354

Graph's {dateTime,timeZone} to a naive wall clock in `$tz`.

```php
function toLocal(array $slot, string $tz): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$slot` | `array` |  | Graph's {dateTime, timeZone} pair for an event's start or end |
| `$tz` | `string` |  | the PHP time zone to give the naive wall-clock time in |

### callAbsolute

*private* - line 367

A GET against a URL Graph gave us (nextLink / deltaLink are absolute).

```php
function callAbsolute(string $url): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | an absolute Graph URL - a nextLink, a deltaLink, or the first delta query |

### createSubscription

*public* - line 401

Subscribes to updates and deletions in the mailbox's calendar, posting to `$notifyUrl` with `$secret` as clientState, for SUBSCRIPTION_MINUTES.

```php
function createSubscription(string $calendarAddress, string $notifyUrl, string $secret): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the analyst's mailbox address (UPN), as Graph knows it |
| `$notifyUrl` | `string` |  | the public HTTPS endpoint Graph posts notifications to (Graph checks it during this call) |
| `$secret` | `string` |  | sent as clientState and echoed back in every notification, to prove it came from Graph |

**Returns:** array  ['id' => subscription id, 'expires' => naive local datetime]

### renewSubscription

*public* - line 425

Extends a Graph subscription by SUBSCRIPTION_MINUTES; throws CalendarSubscriptionMissing when it has already lapsed.

```php
function renewSubscription(string $subscriptionId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$subscriptionId` | `string` |  | Graph's subscription id, as `createSubscription()` returned it |

### deleteSubscription

*public* - line 446

Removes a Graph subscription; one already gone counts as success.

```php
function deleteSubscription(string $subscriptionId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$subscriptionId` | `string` |  | Graph's subscription id, as `createSubscription()` returned it |

### expiryToLocal

*private* - line 458

Graph's UTC expiry to the naive local datetime the column stores.

```php
function expiryToLocal(?string $utc): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$utc` | `?string` |  | Graph's expirationDateTime (UTC, ISO 8601); null uses now plus SUBSCRIPTION_MINUTES |

### verifyTarget

*public* - line 471

True when Graph can read the mailbox's calendar - i.e. the address exists and the app can reach it.

```php
function verifyTarget(string $calendarAddress): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$calendarAddress` | `string` |  | the mailbox address (UPN) to check |

### briefly

*private* - line 488

Graph error bodies are long and carry request ids. Keep enough to diagnose without putting a wall of JSON in last_error, which is 500 characters and is shown to a human.

```php
function briefly(?string $raw): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `?string` |  | the raw response body from Graph or the token endpoint |

