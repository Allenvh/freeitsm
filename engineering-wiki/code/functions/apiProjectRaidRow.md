# apiProjectRaidRow()

One RAID entry of a project, or answers 404 and stops.

```php
function apiProjectRaidRow(PDO $conn, int $pid, int $rid): array
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 495.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$pid` | `int` |  | the projects id |
| `$rid` | `int` |  | the RAID entry id, which must belong to that project |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectRaidCreate()](/code/functions/apiProjectRaidCreate.md), [apiProjectRaidUpdate()](/code/functions/apiProjectRaidUpdate.md), [apiProjectRaidDelete()](/code/functions/apiProjectRaidDelete.md)

