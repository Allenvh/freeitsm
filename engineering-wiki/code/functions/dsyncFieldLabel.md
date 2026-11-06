# dsyncFieldLabel()

A readable name for a person column, for the run log.

The log is written once and read later, so it holds English prose rather than
translation keys — the same choice the rest of the detail text already makes.
A column with no entry here falls back to itself, so adding a field to the
sync can never make the log throw.

```php
function dsyncFieldLabel(string $col): string
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 720.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$col` | `string` |  | a users column name, e.g. 'job_title' |

**Returns:** `string`

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncNewPersonSummary()](/code/functions/dsyncNewPersonSummary.md), [dsyncApplyToExisting()](/code/functions/dsyncApplyToExisting.md)

