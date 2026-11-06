# projectAutoHealth()

The automatic health of a live project, from what is actually happening: red - past its target end date with work still open, or a quarter or more of the open work overdue; amber - any open work overdue, a milestone missed (3.3.0), a dependency not arrived or a decision not made by its due date (3.3.0), or the target date within 14 days and less than three quarters done; the two 3.3.0 rules are settings (project_health_milestones / project_health_raid_late: off, amber - the default - or red); green - otherwise. An asset target that is red makes the project red; an amber one makes a green project amber (projectTargetHealth). Finished projects have no health (null). Written in the help page too - keep the two in step.

```php
function projectAutoHealth(array $p, array $stats, ?array $cfg = null): ?string
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 137.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | the projects row - status and target_end_date are read |
| `$stats` | `array` |  | the project's counts from `projectTaskStats()` |
| `$cfg` | `?array` | `null` | the health thresholds from `projectHealthConfig()`; null uses the built-in defaults |

**Returns:** `?string`

## Calls

[projectFinishedStatuses()](/code/functions/projectFinishedStatuses.md), [projectTicketSpike()](/code/functions/projectTicketSpike.md)

## Called from

- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectDecorate()](/code/functions/projectDecorate.md)

