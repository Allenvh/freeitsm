# dsyncNewPersonSummary()

What a person arriving for the first time will actually hold.

Only the details that are present are listed: padding the line with
"Office: (empty)" for everybody who has no office would bury the fields that
do carry something. An account with nothing but a name says so plainly, which
is itself worth seeing before an import.

```php
function dsyncNewPersonSummary(array $p): string
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 745.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | the person as `dsyncMapPerson()` (or the CardDAV mapper) returns it |

**Returns:** `string`

## Calls

[dsyncFieldLabel()](/code/functions/dsyncFieldLabel.md)

## Called from

- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)

