# projectMilestoneState()

done | missed | due - see the header.

```php
function projectMilestoneState(array $m, ?string $today = null): string
```

Defined in [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md), line 38.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$m` | `array` |  | a project_milestones row - due_date and done_date are read |
| `$today` | `?string` | `null` | the date to judge against, 'Y-m-d'; null means today (UTC) |

**Returns:** `string`

## Called from

- [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md)
- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectMilestoneEventFields()](/code/functions/projectMilestoneEventFields.md)
- [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md) - in [projectMilestones()](/code/functions/projectMilestones.md)

