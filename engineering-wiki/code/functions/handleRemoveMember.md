# handleRemoveMember()

Removes one analyst or portal user from a user group.

```php
function handleRemoveMember(PDO $conn, array $input): void
```

Defined in [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md), line 416.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$input` | `array` |  | the decoded JSON request: id (the group), member_type and member_id |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 427 | `handleRemoveMember` | **delete** [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md) |  |

## Called from

- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)

