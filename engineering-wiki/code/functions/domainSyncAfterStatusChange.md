# domainSyncAfterStatusChange()

A status switched to "no alerts" drops its domains from the calendar.

```php
function domainSyncAfterStatusChange(PDO $conn): void
```

Defined in [api/domains/statuses.php](/code/files/api/domains/statuses.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[domainSyncExpiryCalendar()](/code/functions/domainSyncExpiryCalendar.md)

## Called from

- [api/domains/statuses.php](/code/files/api/domains/statuses.php.md)

