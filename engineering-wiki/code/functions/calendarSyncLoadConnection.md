# calendarSyncLoadConnection()

Load a connection with its secrets decrypted, or null.

A connection may BORROW an Azure app registration already configured for a
mailbox (calendar_connections.mailbox_id) instead of carrying its own —
most installs have already done that setup for mail, and adding one Graph
permission to an existing app is far less work than registering a second one.
Resolving that here means providers never have to know about it.

```php
function calendarSyncLoadConnection(PDO $conn, int $connectionId): ?array
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connectionId` | `int` |  | calendar_connections.id; an inactive or missing one gives null |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 148 | `calendarSyncLoadConnection` | read [calendar_connections](/code/tables/calendar_connections.md) |  |
| 156 | `calendarSyncLoadConnection` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## Calls

[calendarSyncDecodeCredentials()](/code/functions/calendarSyncDecodeCredentials.md), [decryptMailboxRow()](/code/functions/decryptMailboxRow.md)

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md) - in [csTakeBack()](/code/functions/csTakeBack.md)
- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncOnlyConnection()](/code/functions/calendarSyncOnlyConnection.md), [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md)
- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncRemoveRow()](/code/functions/calendarSyncRemoveRow.md)

