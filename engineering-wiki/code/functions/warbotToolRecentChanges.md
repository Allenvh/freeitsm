# warbotToolRecentChanges()

recent_changes: up to 20 changes whose work or outage window started in the last N days (default 2, max 30), with state and owner.

```php
function warbotToolRecentChanges(PDO $conn, array $args, int $analystId): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 487.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the model's arguments - untrusted: days (1-30, default 2) |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 494 | `warbotToolRecentChanges` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md), read [analysts](/code/tables/analysts.md) |  |

*Nothing calls this function by name.*

