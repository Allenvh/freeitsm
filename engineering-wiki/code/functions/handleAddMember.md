# handleAddMember()

Add one person, with an optional last day of access.

⚠️ Members are added and removed ONE AT A TIME rather than by posting a
replacement list. A replace-all would have to re-insert every row, which
silently resets each member's `expires_at` and the date they were added — so
editing a group to add one person would quietly give six other people
permanent access. The LMS group editor does exactly that; it gets away with it
only because its membership carries no expiry to lose.

```php
function handleAddMember(PDO $conn, int $analystId, array $input): void
```

Defined in [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md), line 362.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$input` | `array` |  | the decoded JSON request: id (the group), member_type, member_id and an optional expires_on date |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 373 | `handleAddMember` | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 381 | `handleAddMember` | read [analysts](/code/tables/analysts.md) |  |
| 386 | `handleAddMember` | read [users](/code/tables/users.md) |  |
| 402 | `handleAddMember` | **insert** [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md) (`group_id`, `member_type`, `member_id`, `expires_at`, `created_datetime`) |  |

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md), [groupExpiryToUtc()](/code/functions/groupExpiryToUtc.md)

## Called from

- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md)

