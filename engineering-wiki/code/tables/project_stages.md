# project_stages

Phases, stages and sprints in ONE table - the same thing through different methods.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `kind` | `VARCHAR(10) NOT NULL DEFAULT 'phase'` |  |
| `name` | `VARCHAR(150) NOT NULL` |  |
| `goal` | `VARCHAR(500) NULL` |  |
| `start_date` | `DATE NULL` |  |
| `end_date` | `DATE NULL` |  |
| `position` | `INT NOT NULL DEFAULT 0` |  |
| `status` | `VARCHAR(10) NOT NULL DEFAULT 'planned'` |  |
| `gate_decision` | `VARCHAR(20) NULL` |  |
| `gate_notes` | `TEXT NULL` |  |
| `gate_decided_by` | `INT NULL` |  |
| `gate_decided_datetime` | `DATETIME NULL` |  |
| `gate_kind` | `VARCHAR(10) NOT NULL DEFAULT 'standard'` | 3.3.0 standard \| golive |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):309 | `ProjectsService::applyMethodology` | update | `kind`, `updated_datetime` |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):321 | `ProjectsService::applyMethodology` | update | `status` |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):367 | `ProjectsService::saveStage` | update | `name`, `goal`, `start_date`, `end_date`, `status`, `updated_datetime` |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):381 | `ProjectsService::saveStage` | insert | `project_id`, `kind`, `name`, `goal`, `start_date`, `end_date`, `position`, `status`, `created_datetime`, `updated_datetime` |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):410 | `ProjectsService::deleteStage` | delete |  |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):430 | `ProjectsService::reorderStages` | update | `position` |  |
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):63 | `ProjectTemplatesService::createFromTemplate` | insert | `project_id`, `kind`, `name`, `goal`, `start_date`, `end_date`, `position`, `status`, `created_datetime`, `updated_datetime` |  |
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):81 | `ProjectTemplatesService::createFromTemplate` | update | `gate_kind` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):775 | `ProjectToolsService::decideGate` | update | `gate_decision`, `gate_notes`, `gate_decided_by`, `gate_decided_datetime`, `updated_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):782 | `ProjectToolsService::decideGate` | update | `status` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):787 | `ProjectToolsService::decideGate` | update | `status`, `updated_datetime` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1338 | `ProjectToolsService::setGateKind` | update | `gate_kind`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L87 |  |
| [api/tasks/list.php](/code/files/api/tasks/list.php.md) | L169 |  |
| [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) | apiProjectSelect L42, apiProjectStages L316 |  |
| [includes/feature_bingo/cards/projects.php](/code/files/includes/feature_bingo/cards/projects.php.md) | L33 |  |
| [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) | projectAiFacts L127, projectAiFacts L156, projectAiFacts L273 |  |
| [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) | projectAlertsStagesDue L238 |  |
| [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) | projectAssistRows L41, projectAssistOverview L183, projectAssistTasks L278, projectAssistDates L314 |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) | projectChatMaturity L127, projectChatRead L255, projectChatRead L265, projectChatRead L278, projectChatApplyOne L546, projectChatApplyOne L550 |  |
| [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md) | projectSyncCalendar L68 |  |
| [includes/projects/control.php](/code/files/includes/projects/control.php.md) | projectPlanSnapshot L67, projectControlDetail L315 |  |
| [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md) | projectMilestones L48 |  |
| [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) | projectAlertsStalled L119 |  |
| [includes/projects/read.php](/code/files/includes/projects/read.php.md) | projectExceptionColumns L209, projectListRows L324, projectDetail L384 |  |
| [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) | projectGateKindReady L52, projectTemplateCapture L418, projectTemplateCapture L432 |  |
| [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) | rpProjectRows L44, rpProjectsKpis L101, rpProjectsMilestones L192 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::decideGate L752, ProjectToolsService::decideGate L784, ProjectToolsService::items L1145, ProjectToolsService::stageOf L1756 |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md) | ProjectsService::applyMethodology L315, ProjectsService::saveStage L359, ProjectsService::saveStage L380, ProjectsService::loadStage L572, ProjectsService::signoffEvent L841 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L882 |  |

