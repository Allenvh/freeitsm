# projectProposalBlocksStatus()

A pending proposal may only stay proposed or be withdrawn (cancelled).

```php
function projectProposalBlocksStatus(array $project, string $newStatus): bool
```

Defined in [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), line 95.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$project` | `array` |  | the projects row - approval_status is read |
| `$newStatus` | `string` |  | the status the project would move to, e.g. 'active' |

**Returns:** `bool`

## Called from

- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::updateProject`

