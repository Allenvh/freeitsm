# calendarSyncEncodeCredentials()

Encrypt a credentials array for storage.

```php
function calendarSyncEncodeCredentials(array $credentials): string
```

Defined in [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), line 109.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$credentials` | `array` |  | the secrets to store, e.g. ['username' => ..., 'password' => ...] |

**Returns:** `string`

## Calls

[encryptValue()](/code/functions/encryptValue.md)

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)
- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)

