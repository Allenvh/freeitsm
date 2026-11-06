# generateDemoTicketNumber()

Helper: generate unique ticket number

```php
function generateDemoTicketNumber($conn)
```

Defined in [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md), line 208.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 214 | `generateDemoTicketNumber` | read [tickets](/code/tables/tickets.md) |  |

## Called from

- [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md) - in [resolveTokens()](/code/functions/resolveTokens.md)

