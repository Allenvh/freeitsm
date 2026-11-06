# problem_statuses

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `is_closed` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/problem-management/delete_problem_status.php](/code/files/api/problem-management/delete_problem_status.php.md):28 |  | delete |  |  |
| [api/problem-management/save_problem_status.php](/code/files/api/problem-management/save_problem_status.php.md):36 |  | update | `is_default` |  |
| [api/problem-management/save_problem_status.php](/code/files/api/problem-management/save_problem_status.php.md):38 |  | update | `name`, `is_closed`, `colour`, `is_default`, `display_order`, `is_active` |  |
| [api/problem-management/save_problem_status.php](/code/files/api/problem-management/save_problem_status.php.md):43 |  | insert | `name`, `is_closed`, `colour`, `is_default`, `display_order`, `is_active` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2480 |  | insert | `name`, `is_closed`, `colour`, `is_default`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/problem-management/delete_problem_status.php](/code/files/api/problem-management/delete_problem_status.php.md) | L27 |  |
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L22 |  |
| [api/problem-management/get_problem_statuses.php](/code/files/api/problem-management/get_problem_statuses.php.md) | L16 |  |
| [api/problem-management/list.php](/code/files/api/problem-management/list.php.md) | L28, L46 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L2479 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L204 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemSelect L41, apiProblemsList L190, apiProblemStatusesList L419 |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) | analystReferenceRegistry L114, analystReferenceRegistry L125 |  |
| [includes/feature_bingo/cards/problems.php](/code/files/includes/feature_bingo/cards/problems.php.md) | L112 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L233 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewProblem L273 |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md) | ProblemsService::createProblem L55, ProblemsService::loadProblem L426, ProblemsService::resolveStatus L515, ProblemsService::resolveStatus L518 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolKnownErrors L665 |  |

