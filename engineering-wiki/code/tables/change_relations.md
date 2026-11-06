# change_relations

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `change_id` | `INT NOT NULL` |  |
| `related_type` | `VARCHAR(20) NOT NULL` |  |
| `related_id` | `INT NOT NULL` |  |
| `relation_type` | `VARCHAR(30) NOT NULL` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/problems.php](/code/files/includes/services/problems.php.md):244 | `ProblemsService::deleteProblem` | delete |  |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md):382 | `ProblemsService::linkChange` | insert | `change_id`, `related_type`, `related_id`, `relation_type`, `created_by_id`, `created_datetime` |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md):401 | `ProblemsService::unlinkChange` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L51 |  |
| [api/problem-management/list_linkable_changes.php](/code/files/api/problem-management/list_linkable_changes.php.md) | L24 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangesGet L320 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemsGet L241 |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md) | ProblemsService::linkChange L375 |  |

