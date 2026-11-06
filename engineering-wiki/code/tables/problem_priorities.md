# problem_priorities

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/problem-management/delete_problem_priority.php](/code/files/api/problem-management/delete_problem_priority.php.md):28 |  | delete |  |  |
| [api/problem-management/save_problem_priority.php](/code/files/api/problem-management/save_problem_priority.php.md):34 |  | update | `is_default` |  |
| [api/problem-management/save_problem_priority.php](/code/files/api/problem-management/save_problem_priority.php.md):36 |  | update | `name`, `colour`, `is_default`, `display_order`, `is_active` |  |
| [api/problem-management/save_problem_priority.php](/code/files/api/problem-management/save_problem_priority.php.md):41 |  | insert | `name`, `colour`, `is_default`, `display_order`, `is_active` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2494 |  | insert | `name`, `colour`, `is_default`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/problem-management/delete_problem_priority.php](/code/files/api/problem-management/delete_problem_priority.php.md) | L27 |  |
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L22 |  |
| [api/problem-management/get_problem_priorities.php](/code/files/api/problem-management/get_problem_priorities.php.md) | L16 |  |
| [api/problem-management/list.php](/code/files/api/problem-management/list.php.md) | L28 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L2493 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemSelect L41, apiProblemsList L190, apiProblemPrioritiesList L436 |  |
| [includes/feature_bingo/cards/problems.php](/code/files/includes/feature_bingo/cards/problems.php.md) | L127 |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md) | ProblemsService::loadProblem L426, ProblemsService::resolvePriority L541, ProblemsService::resolvePriority L544 |  |

