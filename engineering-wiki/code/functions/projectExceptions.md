# projectExceptions()

A project's tolerance breaches - PRINCE2-style "manage by exception". Only for a live project with the Gates tool on and a tolerance set: time - the target finish (or the active stage's end) is more than the allowed days in the past with work still open; risk - an open risk scores above the allowed score. An exception turns automatic health red. Worked out, never stored.

```php
function projectExceptions(array $p, array $stats): array
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 227.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | the projects row with `projectExceptionColumns()` added (tol_time, tol_risk, tol_cost, max_risk, active_stage_end) |
| `$stats` | `array` |  | the project's counts from `projectTaskStats()`, budget included |

**Returns:** `array`

## Calls

[projectEnabledTools()](/code/functions/projectEnabledTools.md), [projectFinishedStatuses()](/code/functions/projectFinishedStatuses.md)

## Called from

- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectDecorate()](/code/functions/projectDecorate.md)

