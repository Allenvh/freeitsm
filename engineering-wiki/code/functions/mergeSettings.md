# mergeSettings()

Read the install's merge policy. Defaults are the conservative pair: keep the requester's reference alive, and keep the conversation searchable.

```php
function mergeSettings(PDO $conn): array
```

Defined in [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), line 91.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 99 | `mergeSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/tickets/get_merge_settings.php](/code/files/api/tickets/get_merge_settings.php.md)
- [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) - in [mergeTickets()](/code/functions/mergeTickets.md)

