# problems

Problem Management A Problem is the root cause behind one or more incidents (tickets). It carries RCA, a workaround and a known-error flag, and links to the incidents it explains (problem_tickets) and the change that fixes it (via change_relations). Company- scoped via tenant_id like tickets (NULL = Default), invisible at N=1.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NULL` |  |
| `problem_number` | `VARCHAR(20) NULL` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `description` | `LONGTEXT NULL` |  |
| `status_id` | `INT NULL` |  |
| `priority_id` | `INT NULL` |  |
| `assigned_analyst_id` | `INT NULL` |  |
| `root_cause` | `LONGTEXT NULL` |  |
| `workaround` | `LONGTEXT NULL` |  |
| `is_known_error` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `closed_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/problems.php](/code/files/includes/services/problems.php.md):73 | `ProblemsService::createProblem` | insert | `tenant_id`, `title`, `description`, `status_id`, `priority_id`, `assigned_analyst_id`, `root_cause`, `workaround`, `is_known_error`, `created_by_id` |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md):84 | `ProblemsService::createProblem` | update | `problem_number` |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md):210 | `ProblemsService::updateProblem` | update |  |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md):247 | `ProblemsService::deleteProblem` | delete |  |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md):504 | `ProblemsService::touch` | update | `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md) | L28 |  |
| [api/problem-management/delete_problem_priority.php](/code/files/api/problem-management/delete_problem_priority.php.md) | L24 |  |
| [api/problem-management/delete_problem_status.php](/code/files/api/problem-management/delete_problem_status.php.md) | L24 |  |
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L22 |  |
| [api/problem-management/link_ticket.php](/code/files/api/problem-management/link_ticket.php.md) | L24 |  |
| [api/problem-management/list.php](/code/files/api/problem-management/list.php.md) | L28, L47 |  |
| [api/problem-management/list_linkable_tickets.php](/code/files/api/problem-management/list_linkable_tickets.php.md) | L27 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L243 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L204 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangesGet L320 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemSelect L41, apiProblemsList L190 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L233, projectLinkSearch L362 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L457 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewProblem L273 |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md) | ProblemsService::loadProblem L426 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | analystCanAccessProblem L346 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolKnownErrors L665 |  |

