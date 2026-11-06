# domainWatchMark()

Records that a weekly watch ran for a domain today, keeping only the latest marker row.

```php
function domainWatchMark(PDO $conn, int $id, string $marker): void
```

Defined in [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md), line 148.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain that was scanned |
| `$marker` | `string` |  | the watch's marker name, 'ct_scanned' or 'lookalikes_scanned' |

**Returns:** `void`

**Writes:** domain_audit - the marker row `domainWatchDue()` reads

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 152 | `domainWatchMark` | **delete** [domain_audit](/code/tables/domain_audit.md) |  |

## Called from

- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainScheduledRun()](/code/functions/domainScheduledRun.md)

