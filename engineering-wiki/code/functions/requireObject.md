# requireObject()

'folder' or 'article', and an id that exists. Anything else is refused.

```php
function requireObject(PDO $conn, array $src): array
```

Defined in [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), line 84.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$src` | `array` |  | the request (`$_GET` or the decoded JSON body) carrying object_type and object_id |

**Returns:** `array`

## Called from

- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) - in [handleGet()](/code/functions/handleGet.md), [handleSetMode()](/code/functions/handleSetMode.md), [handleAdd()](/code/functions/handleAdd.md), [handleRemove()](/code/functions/handleRemove.md)

