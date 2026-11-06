# apiSerializeProjectStage()

Stages (phases / stages / sprints)

```php
function apiSerializeProjectStage(array $s): array
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 290.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `array` |  | a project_stages row as `apiProjectStages()` loads it, with task_total and task_done |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectStagesCreate()](/code/functions/apiProjectStagesCreate.md), [apiProjectStagesUpdate()](/code/functions/apiProjectStagesUpdate.md), [apiProjectStagesGate()](/code/functions/apiProjectStagesGate.md)

