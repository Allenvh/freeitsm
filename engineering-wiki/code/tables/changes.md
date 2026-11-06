# changes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NULL` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `change_type_id` | `INT NULL` |  |
| `status_id` | `INT NULL` |  |
| `priority_id` | `INT NULL` |  |
| `impact_id` | `INT NULL` |  |
| `category` | `VARCHAR(100) NULL` |  |
| `requester_id` | `INT NULL` |  |
| `assigned_to_id` | `INT NULL` |  |
| `approver_id` | `INT NULL` |  |
| `approval_datetime` | `DATETIME NULL` |  |
| `work_start_datetime` | `DATETIME NULL` |  |
| `work_end_datetime` | `DATETIME NULL` |  |
| `outage_start_datetime` | `DATETIME NULL` |  |
| `outage_end_datetime` | `DATETIME NULL` |  |
| `description` | `LONGTEXT NULL` |  |
| `reason_for_change` | `LONGTEXT NULL` |  |
| `risk_evaluation` | `LONGTEXT NULL` |  |
| `test_plan` | `LONGTEXT NULL` |  |
| `rollback_plan` | `LONGTEXT NULL` |  |
| `post_implementation_review` | `LONGTEXT NULL` |  |
| `risk_likelihood` | `TINYINT NULL` |  |
| `risk_impact_score` | `TINYINT NULL` |  |
| `risk_score` | `TINYINT NULL` |  |
| `risk_level` | `VARCHAR(20) NULL` |  |
| `pir_was_successful` | `TINYINT(1) NULL` |  |
| `pir_actual_start` | `DATETIME NULL` |  |
| `pir_actual_end` | `DATETIME NULL` |  |
| `pir_lessons_learned` | `LONGTEXT NULL` |  |
| `pir_follow_up` | `LONGTEXT NULL` |  |
| `category_id` | `INT NULL` |  |
| `template_id` | `INT NULL` |  |
| `cab_required` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `cab_approval_type` | `VARCHAR(20) NOT NULL DEFAULT 'all'` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `modified_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/change-management/move_to_company.php](/code/files/api/change-management/move_to_company.php.md):62 |  | update | `tenant_id`, `modified_datetime` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):115 | `ChangesService::createChange` | insert | `tenant_id`, `title`, `change_type_id`, `status_id`, `priority_id`, `impact_id`, `category`, `category_id`, `requester_id`, `assigned_to_id` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):364 | `ChangesService::updateChange` | update |  |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):396 | `ChangesService::deleteChange` | delete |  |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):599 | `ChangesService::voteCab` | update | `status_id`, `modified_datetime` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):607 | `ChangesService::voteCab` | update | `status_id`, `approval_datetime`, `modified_datetime` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):678 | `ChangesService::linkTicket` | update | `modified_datetime` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):702 | `ChangesService::unlinkTicket` | update | `modified_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/delete_change_impact.php](/code/files/api/change-management/delete_change_impact.php.md) | L34 |  |
| [api/change-management/delete_change_priority.php](/code/files/api/change-management/delete_change_priority.php.md) | L34 |  |
| [api/change-management/delete_change_status.php](/code/files/api/change-management/delete_change_status.php.md) | L35 |  |
| [api/change-management/delete_change_type.php](/code/files/api/change-management/delete_change_type.php.md) | L34 |  |
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L34 |  |
| [api/change-management/get_approvals.php](/code/files/api/change-management/get_approvals.php.md) | L32, L55, L107, L119 |  |
| [api/change-management/get_calendar_changes.php](/code/files/api/change-management/get_calendar_changes.php.md) | L31 |  |
| [api/change-management/list.php](/code/files/api/change-management/list.php.md) | L40, L87 |  |
| [api/change-management/list_linkable_tickets.php](/code/files/api/change-management/list_linkable_tickets.php.md) | L27 |  |
| [api/change-management/move_to_company.php](/code/files/api/change-management/move_to_company.php.md) | L49 |  |
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L51 |  |
| [api/problem-management/list_linkable_changes.php](/code/files/api/problem-management/list_linkable_changes.php.md) | L32 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L218 |  |
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L39 |  |
| [api/tasks/search_links.php](/code/files/api/tasks/search_links.php.md) | L56 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L220 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangeSelect L57, apiChangesList L258 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemsGet L241 |  |
| [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) | apiTasksGet L351 |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) | analystReferenceRegistry L59, analystReferenceRegistry L60 |  |
| [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md) | projectGateChanges L56 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L198, projectLinkSearch L350, projectUnapprovedChanges L503 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L463 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewChange L231 |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md) | ChangesService::voteCab L576, ChangesService::loadJoined L746 |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md) | ProblemsService::linkChange L365 |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) | TasksService::validateLink L1377 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | analystCanAccessChange L376 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolRecentChanges L494 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L291, getWatchtowerData L315, getWatchtowerData L328, getWatchtowerData L349, getWatchtowerData L376 |  |

