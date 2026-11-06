# problem_notes

Free-text journal notes on a problem (who / when / the note). Distinct from problem_audit, which logs structured field changes.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `problem_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `note` | `LONGTEXT NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/problems.php](/code/files/includes/services/problems.php.md):268 | `ProblemsService::addNote` | insert | `problem_id`, `analyst_id`, `note`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L75 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemNotesList L319 |  |

