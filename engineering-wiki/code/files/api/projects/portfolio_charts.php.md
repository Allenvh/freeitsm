# api/projects/portfolio_charts.php

> API endpoint - 51 lines

GET - what the portfolio's Charts view draws across projects (3.3.0), for the projects this analyst may see (the same scope as list.php): budgets {project_id: {planned, actual, forecast, currency}} - never added across currencies risks [{project_id, title, probability, impact}] - open risks with both scores milestones [{project_id, name, due_date, state}] - every milestone, with its state Progress comes from list.php's own rows.

## The endpoint

| | |
|---|---|
| URL | `api/projects/portfolio_charts.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 |  | read [projects](/code/tables/projects.md) |  |
| 36 |  | read [project_raid](/code/tables/project_raid.md) |  |
| 43 |  | read [project_milestones](/code/tables/project_milestones.md) |  |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md)

**Calls:** [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectBudgetReady()](/code/functions/projectBudgetReady.md), [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectListRows()](/code/functions/projectListRows.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectMilestoneState()](/code/functions/projectMilestoneState.md)

