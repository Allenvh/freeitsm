# projectTicketSpike()

Have this many linked tickets been raised in the last 7 days? (Projects -> Settings -> Health)

```php
function projectTicketSpike(array $stats, array $cfg): bool
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 175.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$stats` | `array` |  | the project's counts from `projectTaskStats()` - tickets_7d is read |
| `$cfg` | `array` |  | the health thresholds from `projectHealthConfig()` - ticket_amber is read (0 = off) |

**Returns:** `bool`

## Called from

- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectAutoHealth()](/code/functions/projectAutoHealth.md), [projectDecorate()](/code/functions/projectDecorate.md)

