# project_milestones

Milestones (3.3.0): the dates a project promises - includes/projects/milestones.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `stage_id` | `INT NULL` |  |
| `name` | `VARCHAR(150) NOT NULL` |  |
| `due_date` | `DATE NOT NULL` |  |
| `done_date` | `DATE NULL` |  |
| `done_by_analyst_id` | `INT NULL` |  |
| `notes` | `VARCHAR(500) NULL` |  |
| `position` | `INT NOT NULL DEFAULT 0` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):407 | `ProjectsService::deleteStage` | update | `stage_id` |  |
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):87 | `ProjectTemplatesService::createFromTemplate` | insert | `project_id`, `stage_id`, `name`, `due_date`, `position`, `created_by_analyst_id`, `created_datetime`, `updated_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1009 | `ProjectToolsService::saveMilestone` | update | `name`, `due_date`, `stage_id`, `notes`, `done_date`, `done_by_analyst_id`, `updated_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1017 | `ProjectToolsService::saveMilestone` | insert | `project_id`, `stage_id`, `name`, `due_date`, `done_date`, `done_by_analyst_id`, `notes`, `position`, `created_by_analyst_id`, `created_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1042 | `ProjectToolsService::deleteMilestone` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md) | L43 |  |
| [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) | projectAlertsMilestones L334 |  |
| [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) | projectAssistDates L320, projectAssistDates L322 |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) | projectChatMaturity L133, projectChatMaturity L134, projectChatRead L278 |  |
| [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md) | projectSyncCalendar L81 |  |
| [includes/projects/control.php](/code/files/includes/projects/control.php.md) | projectPlanSnapshot L73 |  |
| [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md) | projectMilestonesReady L26, projectMilestones L48, projectMilestoneStats L77, projectMilestoneStats L82 |  |
| [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) | projectTemplateCapture L471 |  |
| [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) | rpProjectsMilestones L200 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::saveMilestone L1015, ProjectToolsService::milestone L1057 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L889 |  |

