# calendarSyncAccount()

An analyst's own calendar sign-in, decrypted: ['username' => …, 'password' => …]. Empty when they have not saved one. Never returned to a browser.

```php
function calendarSyncAccount(array $enrolment): array
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 281.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$enrolment` | `array` |  | a calendar_enrolments row - its encrypted 'credentials' column is read |

**Returns:** `array`

## Calls

[calendarSyncDecodeCredentials()](/code/functions/calendarSyncDecodeCredentials.md)

## Called from

- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)
- [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) - in [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md)

