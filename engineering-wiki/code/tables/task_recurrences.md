# task_recurrences

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `mode` | `VARCHAR(12) NOT NULL DEFAULT 'completion'` | completion = count from the day it was finished (Planner's behaviour). schedule   = fixed dates whether or not the last one was done. |
| `freq` | `VARCHAR(10) NOT NULL DEFAULT 'weekly'` |  |
| `interval_n` | `INT NOT NULL DEFAULT 1` |  |
| `weekdays` | `VARCHAR(20) NULL` | weekly: ISO days, "1,4" |
| `month_mode` | `VARCHAR(4) NULL` | dom \| nth |
| `day_of_month` | `INT NULL` | 1..31, or -1 = last day |
| `nth` | `INT NULL` | 1..5, or -1 = last |
| `nth_weekday` | `INT NULL` | ISO 1..7 |
| `month_of_year` | `INT NULL` | yearly: 1..12 |
| `ends_mode` | `VARCHAR(12) NOT NULL DEFAULT 'never'` |  |
| `ends_on` | `DATE NULL` |  |
| `max_occurrences` | `INT NULL` |  |
| `occurrences_created` | `INT NOT NULL DEFAULT 1` | Starts at 1: the task the series was created from is its first occurrence, so "after 6" produces six tasks in total and not seven. |
| `copy_description` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `copy_subtasks` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `copy_assignee` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `copy_tags` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `copy_links` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `copy_attachments` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `next_due_date` | `DATE NULL` | schedule mode only: the date the worker should next produce one for. |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tasks/save_recurrence.php](/code/files/api/tasks/save_recurrence.php.md):59 |  | update | `is_active`, `updated_datetime` |  |
| [api/tasks/save_recurrence.php](/code/files/api/tasks/save_recurrence.php.md):96 |  | update |  |  |
| [api/tasks/save_recurrence.php](/code/files/api/tasks/save_recurrence.php.md):101 |  | insert |  |  |
| [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md):391 | `TaskRecurrence::runDue` | update | `next_due_date` |  |
| [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md):487 | `TaskRecurrence::deactivate` | update | `is_active`, `updated_datetime` |  |
| [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md):819 | `TaskRecurrence::createOccurrence` | update | `occurrences_created`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L155 |  |
| [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md) | TaskRecurrence::ruleForTask L257, TaskRecurrence::runDue L339, TaskRecurrence::project L426 |  |

