# calendarSyncDecodeCredentials()

Decrypt + JSON-decode a stored credentials blob (never throws).

```php
function calendarSyncDecodeCredentials($stored): array
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 92.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$stored` |  |  | the encrypted credentials column as read from the database; null or '' gives [] |

**Returns:** `array`

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)
- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncLoadConnection()](/code/functions/calendarSyncLoadConnection.md), [calendarSyncAccount()](/code/functions/calendarSyncAccount.md)

