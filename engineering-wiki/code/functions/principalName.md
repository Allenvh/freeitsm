# principalName()

Resolve a principal to something a human can check.

A principal whose row has since been deleted is named as such rather than
shown blank: an unexplained empty line in a permissions list is the sort of
thing people leave alone forever because they dare not remove it.

```php
function principalName(PDO $conn, string $type, int $id): string
```

Defined in [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), line 216.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$type` | `string` |  | the principal_type: 'analyst', 'team', 'user' or 'user_group' |
| `$id` | `int` |  | the principal's id in its own table |

**Returns:** `string`

## Called from

- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) - in [handleGet()](/code/functions/handleGet.md), [resolveInherited()](/code/functions/resolveInherited.md)

