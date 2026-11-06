# includes/projects/assistant.php

> PHP - 333 lines

Projects for an assistant (3.3.0): the read-only answers the MCP server (includes/mcp/tools.php) and Warbot (includes/warbot/tools.php) both give - which projects are off track, how one is going, its RAID log, its tasks.

🔑 ONE COPY, TWO DOORS. Moved here from the MCP server when Warbot gained the
same tools, so the two can never describe a project differently. What differs
is only WHOSE projects, and the caller passes that in as a scope:

  `$scope` = [' AND p.tenant_id ...', [args]]   (alias `p`, NULL company = Default)

  MCP     `mcpProjectScopeSql()`      the KEY's effective companies
  Warbot  `activeTenantReadFilter()`  the asking analyst's active company (or
                                    every company they can see in "All"),
                                    the same rule as the Projects portfolio

Health, progress and exceptions come from the same `projectDecorate()` the
screens use, so an assistant can never call a project green that its page
calls red.

Plain text out, "- " lists, no markdown: Warbot's chat shows text as typed,
and an MCP client reads it either way.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectAssistRows()](/code/functions/projectAssistRows.md) | 38 | Decorated project rows (health, progress, exceptions, budget totals) for a WHERE, inside `$scope`. |
| [projectAssistFind()](/code/functions/projectAssistFind.md) | 62 | One project inside `$scope`, by code (PRJ-0042), id or a unique part of its name. Throws ServiceError. |
| [projectAssistMoney()](/code/functions/projectAssistMoney.md) | 88 | Formats an amount with its currency to two decimals; null shows as "-". |
| [projectAssistWhy()](/code/functions/projectAssistWhy.md) | 98 | Why a project is the colour it is, in words. |
| [projectAssistLine()](/code/functions/projectAssistLine.md) | 123 | One project as a single line of text for the assistant - code, name, status, health, priority, progress, target date, manager and why it needs attention. |
| [projectAssistList()](/code/functions/projectAssistList.md) | 138 | args: status (live \| all \| a status), health, q, limit. |
| [projectAssistOverview()](/code/functions/projectAssistOverview.md) | 171 | One project in full. `$withBudget`: the money line - the MCP server gives it to the key's analyst; Warbot leaves it out, because its answer is read by everyone in the channel. |
| [projectAssistRaid()](/code/functions/projectAssistRaid.md) | 246 | args: project, type (risk \| assumption \| issue \| dependency \| decision \| lesson), status (open \| closed \| all). |
| [projectAssistTasks()](/code/functions/projectAssistTasks.md) | 274 | args: project, open_only (default true). Overdue first, then by due date. |
| [projectAssistDates()](/code/functions/projectAssistDates.md) | 307 | The project dates coming up across every project in `$scope` - stage ends, milestones and target finishes in the next N days (default 14), and milestones already missed. Warbot's "is anything big happening this week?". |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 | `projectAssistRows` | read [project_stages](/code/tables/project_stages.md) |  |
| 43 | `projectAssistRows` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 183 | `projectAssistOverview` | read [project_stages](/code/tables/project_stages.md) |  |
| 215 | `projectAssistOverview` | read [project_audit](/code/tables/project_audit.md), read [analysts](/code/tables/analysts.md) |  |
| 278 | `projectAssistTasks` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [analysts](/code/tables/analysts.md), read [project_stages](/code/tables/project_stages.md) |  |
| 314 | `projectAssistDates` | read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md) |  |
| 316 | `projectAssistDates` | read [projects](/code/tables/projects.md) |  |
| 320 | `projectAssistDates` | read [project_milestones](/code/tables/project_milestones.md), read [projects](/code/tables/projects.md) |  |
| 322 | `projectAssistDates` | read [project_milestones](/code/tables/project_milestones.md), read [projects](/code/tables/projects.md) |  |

## How it hangs together

**Includes:** [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md)

**Included by:** [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [projectCode()](/code/functions/projectCode.md), [projectCurrencyOf()](/code/functions/projectCurrencyOf.md), [projectDecorate()](/code/functions/projectDecorate.md), [projectExceptionColumns()](/code/functions/projectExceptionColumns.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectLinkKindAllowed()](/code/functions/projectLinkKindAllowed.md), [projectMilestones()](/code/functions/projectMilestones.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectStatuses()](/code/functions/projectStatuses.md), [projectTaskStats()](/code/functions/projectTaskStats.md), [projectUnapprovedChanges()](/code/functions/projectUnapprovedChanges.md)

