# projectFlowReady()

Projects - task flow over time (3.3.0): the cumulative flow diagram.

Nothing records when a task changes status, so the flow is SNAPSHOTTED: how
many of a project's tasks sit in each status, once per day per project
(project_task_flow). Today's row is rewritten every time somebody looks
(the project page) and every alert scan (cron hourly, or the portfolio's
fallback), so the last write of the day stands for the day. Days that
nobody and nothing looked at are carried forward on read.

🔑 BEFORE THE FIRST SNAPSHOT only two things are known about a task: when it
was created and when it was finished. Those days are RECONSTRUCTED as two
bands - finished, and "open (status not recorded)" - and the chart marks the
day real history starts. Never invent the statuses in between.

```php
function projectFlowReady(PDO $conn): bool
```

Defined in [includes/projects/flow.php](/code/files/includes/projects/flow.php.md), line 18.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 | `projectFlowReady` | read [project_task_flow](/code/tables/project_task_flow.md) |  |

## Called from

- [includes/projects/flow.php](/code/files/includes/projects/flow.php.md) - in [projectFlowSnapshot()](/code/functions/projectFlowSnapshot.md), [projectFlow()](/code/functions/projectFlow.md)

