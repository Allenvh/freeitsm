# managerGrantMembers()

The people one line refers to, within the base (company, not-self, leavers). Used for both grants and exclusions.

```php
function managerGrantMembers(
    PDO $conn,
    array $g,
    int $managerUserId,
    string $base,
    array $baseArgs
): array
```

Defined in [includes/managers.php](/code/files/includes/managers.php.md), line 182.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$g` | `array` |  | one manager_grants line (grant_type, target_id, target_value); grant_type is everyone, user, group, department or reports |
| `$managerUserId` | `int` |  | the manager's users.id - the top of the reporting line for 'reports' |
| `$base` | `string` |  | the condition on `users u` from `managerReachSql()` |
| `$baseArgs` | `array` |  | the parameters for `$base` |

**Returns:** int[]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 191 | `managerGrantMembers` | read [users](/code/tables/users.md) |  |
| 193 | `managerGrantMembers` | read [users](/code/tables/users.md) |  |
| 198 | `managerGrantMembers` | read [users](/code/tables/users.md), read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md), read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 208 | `managerGrantMembers` | read [users](/code/tables/users.md) |  |
| 219 | `managerGrantMembers` | read [users](/code/tables/users.md) |  |

## Called from

- [api/system/managers.php](/code/files/api/system/managers.php.md)
- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) - in [maState()](/code/functions/maState.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managerTeamUserIds()](/code/functions/managerTeamUserIds.md)

