# includes/projects/milestones.php

> PHP - 93 lines

Projects - milestones (3.3.0): the dates a project promises, as diamonds on the Timeline, the Overview, the Calendar and in the health.

A milestone is a NAMED DATE, not work: "Move day", "Go-live", "Board sign-off".
The work is tasks; a milestone is what the tasks are racing towards. It may
sit in a stage (it is drawn in that stage's band) or belong to the whole
project (stage_id NULL).

Its state is worked out, never stored, the same rule as health:
  done    - done_date is set ("reached"); met if done on or before due_date
  missed  - not done and due_date is in the past
  due     - not done, today or later

🔑 A MISSED MILESTONE TURNS AUTOMATIC HEALTH AMBER (projectAutoHealth), the
same weight as an overdue task: a date the project promised has gone by. It
never makes a project red on its own - that is what the time tolerance is for.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectMilestonesReady()](/code/functions/projectMilestonesReady.md) | 22 | Has Database Verification created the table? Everything here is quiet before it. |
| [projectMilestoneState()](/code/functions/projectMilestoneState.md) | 38 | done \| missed \| due - see the header. |
| [projectMilestones()](/code/functions/projectMilestones.md) | 45 | One project's milestones in date order, each with its state, for the project page. |
| [projectMilestoneStats()](/code/functions/projectMilestoneStats.md) | 71 | Per project: how many milestones are missed, and the next one still to come ({id, name, due_date} or null) - for health and the portfolio card. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 | `projectMilestonesReady` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 48 | `projectMilestones` | read [project_milestones](/code/tables/project_milestones.md), read [project_stages](/code/tables/project_stages.md), read [analysts](/code/tables/analysts.md) |  |
| 77 | `projectMilestoneStats` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 82 | `projectMilestoneStats` | read [project_milestones](/code/tables/project_milestones.md) |  |

## How it hangs together

**Included by:** [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

