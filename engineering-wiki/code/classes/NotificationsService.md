# NotificationsService

> Class in [includes/services/notifications.php](/code/files/includes/services/notifications.php.md), line 42

## Methods

| Method | What it does |
|---|---|
| [portalUser()](#portaluser) | A portal user as a recipient. Anything else passed where a recipient is expected is an analyst id, so every existing caller is unchanged. |
| [who()](#who) | [table, id column, id, is portal] for a recipient. The ONLY place that choice is made. |
| [duringBulk()](#duringbulk) | Run `$fn` with bulk suppression on, restoring the previous state whatever happens. |
| [inBulk()](#inbulk) | True while a `duringBulk()` call is running. |
| [types()](#types) | Which event types put something in the bell, and whether they do so by default. The value is the DEFAULT — every one of these is switchable per analyst under Preferences. |
| [typeEnabled()](#typeenabled) | True when this analyst wants this type in their bell. |
| [preferences()](#preferences) | The analyst's per-type overrides, as [event_type => bool]. |
| [effectivePreferences()](#effectivepreferences) | The full effective map for the settings UI: every type with its current state. |
| [notify()](#notify) | Write a notification, applying every noise rule. |
| [unreadCount()](#unreadcount) | Unread count for the badge. Cheap — this is polled by every open tab. |
| [listFor()](#listfor) | Newest first. Unread first is deliberately NOT done — recency is the useful order. |
| [linkFor()](#linkfor) | Where clicking a notification goes. |
| [portalLinkFor()](#portallinkfor) | Where a portal user's notification goes. Only tickets so far; the ticket page itself decides - through `portalTicketAccess()` - whether they may see it, so a stale notification can never be a way in. |
| [markRead()](#markread) | Mark specific ids read. Scoped to the analyst, so ids from elsewhere do nothing. |
| [markAllRead()](#markallread) | Marks every unread notification for the recipient (an analyst, or a portal user) as read and returns how many. |
| [clear()](#clear) | Remove specific notifications outright (discussion #111). |
| [clearAll()](#clearall) | Empty the panel. |
| [clip()](#clip) | Trimmed text cut to `$max` characters, or null when blank. |

### portalUser

*public static* - line 72

A portal user as a recipient. Anything else passed where a recipient is expected is an analyst id, so every existing caller is unchanged.

```php
function portalUser(int $userId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$userId` | `int` |  | the portal user's id (users.id), not an analyst id |

**Called from:** [api/self-service/notifications.php](/code/files/api/self-service/notifications.php.md)

### who

*private static* - line 82

[table, id column, id, is portal] for a recipient. The ONLY place that choice is made.

```php
function who($recipient): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$recipient` |  |  | an analyst id (int), or a portal user as made by `portalUser()` |

### duringBulk

*public static* - line 98

Run `$fn` with bulk suppression on, restoring the previous state whatever happens.

```php
function duringBulk(callable $fn)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fn` | `callable` |  | the bulk work to run; its return value is passed back |

**Called from:** [api/tickets/bulk_delete_tickets.php](/code/files/api/tickets/bulk_delete_tickets.php.md), [api/tickets/bulk_update_tickets.php](/code/files/api/tickets/bulk_update_tickets.php.md)

### inBulk

*public static* - line 113

True while a `duringBulk()` call is running.

```php
function inBulk(): bool
```

**Called from:** [includes/notifications_router.php](/code/files/includes/notifications_router.php.md)

### types

*public static* - line 127

Which event types put something in the bell, and whether they do so by default. The value is the DEFAULT — every one of these is switchable per analyst under Preferences.

On by default only where the answer to "would you want to be interrupted
for this?" is plainly yes. Everything else is available but quiet, because
a bell that is right 60% of the time gets ignored 100% of the time.

```php
function types(): array
```

**Called from:** [includes/notifications_router.php](/code/files/includes/notifications_router.php.md)

### typeEnabled

*public static* - line 236

True when this analyst wants this type in their bell.

```php
function typeEnabled(PDO $conn, int $analystId, string $eventType): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$eventType` | `string` |  | the event type key, e.g. 'ticket.assigned' - anything not in `types()` is never enabled |

**Called from:** [includes/notifications_router.php](/code/files/includes/notifications_router.php.md)

### preferences

*public static* - line 254

The analyst's per-type overrides, as [event_type => bool].

Stored as one JSON preference rather than one row per type: it is read on
every event written, and a single row keeps that to one lookup.

```php
function preferences(PDO $conn, int $analystId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 258 | `NotificationsService::preferences` | read [user_preferences](/code/tables/user_preferences.md) |  |

### effectivePreferences

*public static* - line 274

The full effective map for the settings UI: every type with its current state.

```php
function effectivePreferences(PDO $conn, int $analystId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [system/preferences/index.php](/code/files/system/preferences/index.php.md)

### notify

*public static* - line 298

Write a notification, applying every noise rule.

Returns the notification id, or null when the rules said no — which is the
common case and is not an error.

`$in`: analyst_id (or portal_user_id), event_type, entity_type, entity_id,
     entity_ref, title, body, actor_id, actor_name

```php
function notify(PDO $conn, array $in): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the notification: analyst_id or portal_user_id, event_type, entity_type (default 'ticket'), entity_id, entity_ref, title, body, actor_id (or actor_user_id for portal), actor_name |

**Called from:** [includes/managers.php](/code/files/includes/managers.php.md), [includes/notifications_router.php](/code/files/includes/notifications_router.php.md)

### unreadCount

*public static* - line 394

Unread count for the badge. Cheap — this is polled by every open tab.

```php
function unreadCount(PDO $conn, $recipient): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$recipient` |  |  | an analyst id (int), or a portal user as made by `portalUser()` |

**Called from:** [api/notifications/clear.php](/code/files/api/notifications/clear.php.md), [api/notifications/get_notifications.php](/code/files/api/notifications/get_notifications.php.md), [api/notifications/mark_read.php](/code/files/api/notifications/mark_read.php.md), [api/self-service/notifications.php](/code/files/api/self-service/notifications.php.md)

### listFor

*public static* - line 414

Newest first. Unread first is deliberately NOT done — recency is the useful order.

```php
function listFor(PDO $conn, $recipient, int $limit = self::LIST_LIMIT): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$recipient` |  |  | an analyst id (int), or a portal user as made by `portalUser()` |
| `$limit` | `int` | `self::LIST_LIMIT` | how many rows to return, clamped to 1..LIST_LIMIT (50) |

**Called from:** [api/notifications/get_notifications.php](/code/files/api/notifications/get_notifications.php.md), [api/self-service/notifications.php](/code/files/api/self-service/notifications.php.md)

### linkFor

*public static* - line 451

Where clicking a notification goes.

Relative to the app root. Tickets deep-link to the ticket in the inbox;
anything without a known destination returns null and the bell renders it
as text rather than a dead link.

```php
function linkFor(string $entityType, int $entityId): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$entityType` | `string` |  | what the notification is about, e.g. 'ticket', 'task', 'project' |
| `$entityId` | `int` |  | the id of that record |

**Called from:** [includes/notifications_router.php](/code/files/includes/notifications_router.php.md)

### portalLinkFor

*public static* - line 472

Where a portal user's notification goes. Only tickets so far; the ticket page itself decides - through `portalTicketAccess()` - whether they may see it, so a stale notification can never be a way in.

```php
function portalLinkFor(string $entityType, int $entityId): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$entityType` | `string` |  | what the notification is about - only 'ticket' gets a link |
| `$entityId` | `int` |  | the ticket id |

### markRead

*public static* - line 483

Mark specific ids read. Scoped to the analyst, so ids from elsewhere do nothing.

```php
function markRead(PDO $conn, $recipient, array $ids): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$recipient` |  |  | an analyst id (int), or a portal user as made by `portalUser()` |
| `$ids` | `array` |  | notification ids to mark; non-positive values are dropped, other people's ids match nothing |

**Called from:** [api/notifications/mark_read.php](/code/files/api/notifications/mark_read.php.md), [api/self-service/notifications.php](/code/files/api/self-service/notifications.php.md)

### markAllRead

*public static* - line 505

Marks every unread notification for the recipient (an analyst, or a portal user) as read and returns how many.

```php
function markAllRead(PDO $conn, $recipient): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$recipient` |  |  | an analyst id (int), or a portal user as made by `portalUser()` |

**Called from:** [api/notifications/mark_read.php](/code/files/api/notifications/mark_read.php.md), [api/self-service/notifications.php](/code/files/api/self-service/notifications.php.md)

### clear

*public static* - line 532

Remove specific notifications outright (discussion #111).

A hard DELETE, deliberately. This table is a display cache for the bell and
nothing else in the app reads it — the durable record of what happened lives
in the workflow events and the entity's own audit trail. So a dismissed row
preserves nothing, and a soft-delete column would buy a migration plus a
filter on every read in exchange for that nothing.

Scoped to the analyst for the same reason `markRead()` is: ids belonging to
somebody else match nothing rather than erroring, which keeps a stale open
tab harmless.

```php
function clear(PDO $conn, $recipient, array $ids): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$recipient` |  |  | an analyst id (int), or a portal user as made by `portalUser()` |
| `$ids` | `array` |  | notification ids to delete; non-positive values are dropped, other people's ids match nothing |

**Called from:** [api/notifications/clear.php](/code/files/api/notifications/clear.php.md), [api/self-service/notifications.php](/code/files/api/self-service/notifications.php.md)

### clearAll

*public static* - line 558

Empty the panel.

⚠️ `$includeUnread` defaults to FALSE, and that default IS the safety catch.
Clearing is irreversible, and the rows most worth keeping are precisely the
ones nobody has looked at yet — so unless the analyst deliberately ticks the
box in the confirmation, unread news survives a Clear all.

```php
function clearAll(PDO $conn, $recipient, bool $includeUnread = false): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$recipient` |  |  | an analyst id (int), or a portal user as made by `portalUser()` |
| `$includeUnread` | `bool` | `false` | true to delete unread rows too; false (default) keeps them |

**Called from:** [api/notifications/clear.php](/code/files/api/notifications/clear.php.md), [api/self-service/notifications.php](/code/files/api/self-service/notifications.php.md)

### clip

*private static* - line 576

Trimmed text cut to `$max` characters, or null when blank.

```php
function clip($value, int $max): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the text to tidy (any scalar, or null) |
| `$max` | `int` |  | the most characters to keep |

