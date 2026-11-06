# projects

Projects (3.2.0) - see docs/design/projects.md A container over ordinary tasks (tasks.project_id), scoped like tickets.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NULL` |  |
| `name` | `VARCHAR(200) NOT NULL` |  |
| `summary` | `TEXT NULL` |  |
| `goal` | `VARCHAR(500) NULL` |  |
| `methodology` | `VARCHAR(20) NOT NULL DEFAULT 'simple'` | A LENS, not a schema: switching method converts and deletes nothing. |
| `status` | `VARCHAR(20) NOT NULL DEFAULT 'proposed'` |  |
| `health` | `VARCHAR(10) NOT NULL DEFAULT 'auto'` |  |
| `health_note` | `VARCHAR(500) NULL` |  |
| `priority` | `VARCHAR(10) NOT NULL DEFAULT 'medium'` | 3.3.0: how much the project matters next to the others. The four keys are fixed; the words shown for them are project_priority_labels. |
| `owner_analyst_id` | `INT NULL` |  |
| `start_date` | `DATE NULL` |  |
| `target_end_date` | `DATE NULL` |  |
| `actual_end_date` | `DATE NULL` |  |
| `colour` | `VARCHAR(20) NOT NULL DEFAULT 'coral'` |  |
| `icon` | `VARCHAR(30) NOT NULL DEFAULT 'rocket'` |  |
| `business_case` | `TEXT NULL` |  |
| `tailoring` | `VARCHAR(500) NULL` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `closed_datetime` | `DATETIME NULL` |  |
| `alert_health` | `VARCHAR(10) NULL` | What the alert scan last saw - see includes/projects/alerts.php. |
| `alert_exceptions` | `VARCHAR(100) NULL` |  |
| `currency` | `CHAR(3) NULL` | Budget currency, stamped at creation - see includes/projects/budget.php. |
| `estimated_cost` | `DECIMAL(18,2) NULL` | Intake and approval (3.3.0) - includes/projects/intake.php. |
| `estimated_benefit` | `TEXT NULL` |  |
| `approval_status` | `VARCHAR(10) NULL` |  |
| `approval_by_id` | `INT NULL` |  |
| `approval_datetime` | `DATETIME NULL` |  |
| `approval_notes` | `TEXT NULL` |  |
| `form_submission_id` | `INT NULL` |  |
| `proposed_by_name` | `VARCHAR(200) NULL` |  |
| `proposed_by_email` | `VARCHAR(255) NULL` |  |
| `visibility` | `VARCHAR(10) NOT NULL DEFAULT 'everyone'` | Members-only projects (3.3.0) - includes/projects/visibility.php. |
| `report_schedule` | `VARCHAR(12) NOT NULL DEFAULT 'off'` | Scheduled report drafts (3.3.0) - ProjectReportsService::runSchedules(). |
| `report_schedule_kind` | `VARCHAR(20) NOT NULL DEFAULT 'highlight'` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md):168 | `projectAlertsScan` | update | `alert_health`, `alert_exceptions` |  |
| [includes/projects/budget.php](/code/files/includes/projects/budget.php.md):87 | `projectStampCurrency` | update | `currency` |  |
| [includes/projects/intake.php](/code/files/includes/projects/intake.php.md):205 | `projectCreateFromAction` | update | `form_submission_id`, `proposed_by_name`, `proposed_by_email`, `created_by_id` |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):144 | `ProjectsService::createProject` | insert |  |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):217 | `ProjectsService::updateProject` | update |  |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):285 | `ProjectsService::deleteProject` | delete |  |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):877 | `ProjectsService::decideProposal` | update | `approval_status`, `approval_by_id`, `approval_datetime`, `approval_notes`, `status`, `closed_datetime` |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md):1023 | `ProjectsService::touch` | update | `updated_datetime` |  |
| [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md):250 | `ProjectReportsService::setSchedule` | update | `report_schedule`, `report_schedule_kind` |  |
| [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md):170 | `ProjectTemplatesService::cleanup` | delete |  |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1626 | `ProjectToolsService::decideChangeRequest` | update | `target_end_date` |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md):1998 | `ProjectToolsService::setCurrency` | update | `currency`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/projects/export.php](/code/files/api/projects/export.php.md) | L35 |  |
| [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md) | L23 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L431 |  |
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L87 |  |
| [api/tasks/list.php](/code/files/api/tasks/list.php.md) | L169 |  |
| [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) | apiProjectSelect L45, apiProjectsList L213 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleProjects L492, peopleProjects L500, peopleContractorTasks L789, peopleContractorTasks L793 |  |
| [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) | projectAlertRows L131, projectAlertsScan L154, projectAlertsScan L203, projectAlertsStagesDue L238, projectAlertsMilestones L334 |  |
| [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) | projectAssistRows L43, projectAssistDates L314, projectAssistDates L316, projectAssistDates L320, projectAssistDates L322 |  |
| [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) | projectAlertsBenefits L166 |  |
| [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) | projectBudgetReady L61 |  |
| [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md) | projectSyncCalendar L60, projectSyncCalendar L64, projectSyncCalendar L68, projectSyncCalendar L73, projectSyncCalendar L81, projectSyncCalendar L86 |  |
| [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) | projectCapacity L113, projectCapacity L122 |  |
| [includes/projects/control.php](/code/files/includes/projects/control.php.md) | projectPlanSnapshot L56, projectBaselineAuto L265 |  |
| [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) | projectIntakeReady L38, projectProposalDetail L108, projectCreateFromAction L207 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkProjectRows L402, projectsPickableFor L457 |  |
| [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) | projectAlertsOverdueDigest L39, projectAlertsStalled L92, projectAlertsStalled L104, projectAlertsStalled L119, projectAlertsStalled L130, projectNudgeProjects L178 |  |
| [includes/projects/read.php](/code/files/includes/projects/read.php.md) | projectTaskStats L68, projectListRows L328, projectPriorityColumn L351, projectDetail L376 |  |
| [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) | projectTargetsFor L199 |  |
| [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md) | projectVisibilityReady L44, projectIdVisibleTo L103 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L490, recentTrailLabelsForType L492 |  |
| [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) | rpProjectRows L47, rpProjectsKpis L101, rpProjectsMilestones L192, rpProjectsMilestones L200, rpProjectsRisks L243, rpProjectChoices L286 |  |
| [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) | statusPlannedList L165 |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) | ContractsService::assertMovable L348 |  |
| [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) | ProjectReportsService::scheduleReady L219, ProjectReportsService::schedule L229, ProjectReportsService::recipients L268, ProjectReportsService::runSchedules L389 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::announce L2024 |  |
| [includes/services/projects.php](/code/files/includes/services/projects.php.md) | ProjectsService::loadRow L543, ProjectsService::proposalEvent L906 |  |
| [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) | tasksContractorEmail L164 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | analystCanAccessProject L404 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L863 |  |

