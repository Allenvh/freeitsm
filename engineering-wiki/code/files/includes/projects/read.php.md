# includes/projects/read.php

> PHP - 429 lines

Projects - the reads the screens share: the portfolio and one project.

Reads live here, not in the service (writes are unified, reads stay per
surface - the Service-Layer rule). The REST API will have its own serialiser.

🔑 SCOPE IN SQL, NOT IN JS. The portfolio is filtered by company in the query
(activeTenantReadFilter - one company, or every company the analyst can see in
the "All companies" view). A project the analyst may not see is never sent.

🔑 PROGRESS AND HEALTH ARE WORKED OUT, NEVER STORED. Progress is the share of
the project's top-level tasks that are in a closed status; automatic health is
derived from dates and overdue work (projectAutoHealth). Storing either would
let it go stale the moment somebody ticked a task on the Tasks board.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectTaskStats()](/code/functions/projectTaskStats.md) | 31 | Per-project task counts in one query: total, done, overdue (top-level tasks only), plus targets_health - the worst of the project's asset targets (includes/projects/targets.php) - so every screen's health agrees. |
| [projectAutoHealth()](/code/functions/projectAutoHealth.md) | 137 | The automatic health of a live project, from what is actually happening: red - past its target end date with work still open, or a quarter or more of the open work overdue; amber - any open work overdue, a milestone missed (3.3.0), a dependency not arrived or a decision not made by its due date (3.3.0), or the target date within 14 days and less than three quarters done; the two 3.3.0 rules are settings (project_health_milestones / project_health_raid_late: off, amber - the default - or red); green - otherwise. An asset target that is red makes the project red; an amber one makes a green project amber (projectTargetHealth). Finished projects have no health (null). Written in the help page too - keep the two in step. |
| [projectTicketSpike()](/code/functions/projectTicketSpike.md) | 175 | Have this many linked tickets been raised in the last 7 days? (Projects -> Settings -> Health) |
| [projectHealthConfig()](/code/functions/projectHealthConfig.md) | 182 | The Health tab's thresholds, as numbers. |
| [projectsPhase2Ready()](/code/functions/projectsPhase2Ready.md) | 195 | Has Database Verification created the phase 2 tables? The portfolio query names them. |
| [projectExceptionColumns()](/code/functions/projectExceptionColumns.md) | 206 | The columns the exceptions are read from, as SQL - NULLs before Verification. |
| [projectExceptions()](/code/functions/projectExceptions.md) | 227 | A project's tolerance breaches - PRINCE2-style "manage by exception". Only for a live project with the Gates tool on and a tolerance set: time - the target finish (or the active stage's end) is more than the allowed days in the past with work still open; risk - an open risk scores above the allowed score. An exception turns automatic health red. Worked out, never stored. |
| [projectDecorate()](/code/functions/projectDecorate.md) | 260 | Add progress, counts and the health actually shown to a project row. |
| [projectCode()](/code/functions/projectCode.md) | 292 | The reference people say out loud: PRJ-0042. |
| [projectListRows()](/code/functions/projectListRows.md) | 302 | The portfolio, scoped to what this analyst may see. |
| [projectPriorityColumn()](/code/functions/projectPriorityColumn.md) | 347 | p.priority, or 'medium' before Database Verification has added the column (3.3.0). |
| [projectEstimatesReady()](/code/functions/projectEstimatesReady.md) | 358 | Has Database Verification added tasks.estimate_hours (3.3.0)? Reads stay working before it. |
| [projectDetail()](/code/functions/projectDetail.md) | 373 | One project for its page: the row, its time boxes with their counts, its tasks and its history. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 | `projectTaskStats` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 54 | `projectTaskStats` | read [project_tickets](/code/tables/project_tickets.md), read [tickets](/code/tables/tickets.md) |  |
| 68 | `projectTaskStats` | read [projects](/code/tables/projects.md) |  |
| 84 | `projectTaskStats` | read [project_raid](/code/tables/project_raid.md) |  |
| 199 | `projectsPhase2Ready` | read [project_tolerances](/code/tables/project_tolerances.md) |  |
| 199 | `projectsPhase2Ready` | read [project_raid](/code/tables/project_raid.md) |  |
| 209 | `projectExceptionColumns` | read [project_raid](/code/tables/project_raid.md), read [project_tolerances](/code/tables/project_tolerances.md), read [project_stages](/code/tables/project_stages.md) |  |
| 324 | `projectListRows` | read [project_stages](/code/tables/project_stages.md) |  |
| 328 | `projectListRows` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |
| 351 | `projectPriorityColumn` | read [projects](/code/tables/projects.md) |  |
| 362 | `projectEstimatesReady` | read [tasks](/code/tables/tasks.md) |  |
| 376 | `projectDetail` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |
| 384 | `projectDetail` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [project_stages](/code/tables/project_stages.md) |  |
| 392 | `projectDetail` | read [tasks](/code/tables/tasks.md) |  |
| 397 | `projectDetail` | read [task_time_entries](/code/tables/task_time_entries.md), read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |
| 414 | `projectDetail` | read [project_audit](/code/tables/project_audit.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), [includes/projects/control.php](/code/files/includes/projects/control.php.md), [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md)

**Included by:** [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md), [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md)

**Calls:** [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [projectBenefitStats()](/code/functions/projectBenefitStats.md), [projectBudgetReady()](/code/functions/projectBudgetReady.md), [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectChangeStats()](/code/functions/projectChangeStats.md), [projectEnabledTools()](/code/functions/projectEnabledTools.md), [projectFinishedStatuses()](/code/functions/projectFinishedStatuses.md), [projectMilestoneStats()](/code/functions/projectMilestoneStats.md), [projectProposalApprovalColumn()](/code/functions/projectProposalApprovalColumn.md), [projectSetting()](/code/functions/projectSetting.md), [projectStatuses()](/code/functions/projectStatuses.md), [projectTargetsFor()](/code/functions/projectTargetsFor.md), [projectTargetsWorst()](/code/functions/projectTargetsWorst.md), [projectVisibilityColumn()](/code/functions/projectVisibilityColumn.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md), [tasksWithContractors()](/code/functions/tasksWithContractors.md)

