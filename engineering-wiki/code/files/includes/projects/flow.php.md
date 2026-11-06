# includes/projects/flow.php

> PHP - 106 lines

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

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectFlowReady()](/code/functions/projectFlowReady.md) | 18 | Projects - task flow over time (3.3.0): the cumulative flow diagram. |
| [projectFlowSnapshot()](/code/functions/projectFlowSnapshot.md) | 33 | Record today's counts by status for these projects (top-level tasks). Quiet: never breaks its caller. |
| [projectFlow()](/code/functions/projectFlow.md) | 59 | The flow for one project's chart: statuses [{id, name, closed}] in board order (the install's task statuses) points [{d, counts: {status_id\|'open'\|'done': n}, recorded: bool}] from the first recorded day (null = none yet) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 | `projectFlowReady` | read [project_task_flow](/code/tables/project_task_flow.md) |  |
| 39 | `projectFlowSnapshot` | read [tasks](/code/tables/tasks.md) |  |
| 43 | `projectFlowSnapshot` | **delete** [project_task_flow](/code/tables/project_task_flow.md) |  |
| 44 | `projectFlowSnapshot` | **insert** [project_task_flow](/code/tables/project_task_flow.md) (`project_id`, `day`, `status_id`, `task_count`) |  |
| 63 | `projectFlow` | read [task_statuses](/code/tables/task_statuses.md) |  |
| 66 | `projectFlow` | read [project_task_flow](/code/tables/project_task_flow.md) |  |
| 73 | `projectFlow` | read [tasks](/code/tables/tasks.md) |  |
| 82 | `projectFlow` | read [tasks](/code/tables/tasks.md) |  |

## How it hangs together

**Included by:** [api/projects/get.php](/code/files/api/projects/get.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md)

