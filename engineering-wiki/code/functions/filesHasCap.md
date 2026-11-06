# filesHasCap()

True when the analyst holds the capability; a failed lookup counts as no.

```php
function filesHasCap(PDO $conn, int $analystId, string $cap): bool
```

Defined in [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), line 75.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$cap` | `string` |  | the capability key, normally a Cap:: constant such as Cap::FILES_FOLDERS |

**Returns:** `bool`

## Calls

[analystHasCapability()](/code/functions/analystHasCapability.md)

## Called from

- [api/files/folder.php](/code/files/api/files/folder.php.md)
- [api/files/list.php](/code/files/api/files/list.php.md)
- [api/files/permissions.php](/code/files/api/files/permissions.php.md)
- [api/files/transfer.php](/code/files/api/files/transfer.php.md)

