# handleGet()

> [!NOTE]
> Defined in 3 places (each behind a function_exists() check, or in files never loaded together): [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md), [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)

The current state of one object's access.

Returns the names as well as the ids: a list of "analyst 7, team 3" is not
something anybody can check, and a permissions screen you cannot read is a
permissions screen nobody will audit.

```php
function handleGet(PDO $conn, array $src): void
```

Defined in [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), line 112.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$src` | `array` |  | the query string (`$_GET`): object_type ('folder' or 'article') and object_id |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 116 | `handleGet` | read [knowledge_acl](/code/tables/knowledge_acl.md) |  |

## Calls

[principalName()](/code/functions/principalName.md), [requireObject()](/code/functions/requireObject.md), [resolveInherited()](/code/functions/resolveInherited.md)

## Called from

- [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md)
- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md)
- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)

