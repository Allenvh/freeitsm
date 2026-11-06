# includes/services/task_recurrence.php

> PHP - 846 lines

Task recurrence — the date engine and the spawning of occurrences.

Discussion #94 (dschipfel): recurring tasks in the style of Microsoft Planner.

── The model ────────────────────────────────────────────────────────────────
A recurrence is a SERIES, not a template. The rule lives in one row of
task_recurrences; every task the series produces carries recurrence_id (the
series) and recurrence_master_id (the FIRST task of the series, so any
occurrence can point a reader back to where it started).

That mirrors how people describe it - "this task repeats" - rather than
introducing a hidden template task that is not real work and would show up in
every list, count and report that was written before recurrence existed.

── Two ways to repeat, and they are genuinely different ─────────────────────
  'completion'  the next occurrence is created when this one is COMPLETED,
                counted from the completion date. A monthly backup check you
                finish four days late should next be due a month after you did
                it, not a month after it was theoretically due. This is what
                Planner does and what most people mean.
  'schedule'    occurrences appear on fixed dates whether or not the previous
                one was finished. A compliance review happens on the 1st
                whether or not last month's was signed off, and the fact that
                last month's is still open is the point.

The distinction matters because 'schedule' can produce a backlog and
'completion' cannot. Only 'schedule' needs anything to run in the background.

⚠️ All arithmetic here is on DATES, never datetimes. A due date is a calendar
day, not an instant: "the 1st" means the 1st wherever the reader is. Passing a
datetime through this would reintroduce GH #116 by another route.

## Classes

- [TaskRecurrence](/code/classes/TaskRecurrence.md) (class, line 36) - Task recurrence — the date engine and the spawning of occurrences.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 257 | `TaskRecurrence::ruleForTask` | read [task_recurrences](/code/tables/task_recurrences.md), read [tasks](/code/tables/tasks.md) |  |
| 339 | `TaskRecurrence::runDue` | read [task_recurrences](/code/tables/task_recurrences.md) |  |
| 391 | `TaskRecurrence::runDue` | **update** [task_recurrences](/code/tables/task_recurrences.md) (`next_due_date`) |  |
| 426 | `TaskRecurrence::project` | read [task_recurrences](/code/tables/task_recurrences.md), read [tasks](/code/tables/tasks.md) |  |
| 487 | `TaskRecurrence::deactivate` | **update** [task_recurrences](/code/tables/task_recurrences.md) (`is_active`, `updated_datetime`) |  |
| 500 | `TaskRecurrence::occurrenceExistsFor` | read [tasks](/code/tables/tasks.md) |  |
| 683 | `TaskRecurrence::taskRow` | read [tasks](/code/tables/tasks.md) |  |
| 697 | `TaskRecurrence::latestOccurrence` | read [tasks](/code/tables/tasks.md) |  |
| 720 | `TaskRecurrence::createOccurrence` | read [tasks](/code/tables/tasks.md) |  |
| 733 | `TaskRecurrence::createOccurrence` | **insert** [tasks](/code/tables/tasks.md) (`title`, `description`, `status_id`, `priority_id`, `start_date`, `due_date`, `assigned_analyst_id`, `assigned_team_id`, `ticket_id`, `change_id`, `contract_id`, `tenant_id`) |  |
| 764 | `TaskRecurrence::createOccurrence` | **insert** [task_tag_map](/code/tables/task_tag_map.md) (`task_id`, `tag_id`) |  |
| 773 | `TaskRecurrence::createOccurrence` | read [tasks](/code/tables/tasks.md) |  |
| 778 | `TaskRecurrence::createOccurrence` | **insert** [tasks](/code/tables/tasks.md) (`title`, `status_id`, `priority_id`, `assigned_analyst_id`, `assigned_team_id`, `parent_task_id`, `tenant_id`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 798 | `TaskRecurrence::createOccurrence` | **insert** [document_links](/code/tables/document_links.md) (`document_id`, `parent_type`, `parent_id`, `linked_by_id`, `created_datetime`) |  |
| 819 | `TaskRecurrence::createOccurrence` | **update** [task_recurrences](/code/tables/task_recurrences.md) (`occurrences_created`, `updated_datetime`) |  |
| 840 | `TaskRecurrence::defaultOpenStatusId` | read [task_statuses](/code/tables/task_statuses.md) |  |

## How it hangs together

**Includes:** [includes/services/checklists.php](/code/files/includes/services/checklists.php.md)

**Included by:** [api/tasks/projected.php](/code/files/api/tasks/projected.php.md), [api/tasks/recurrence_preview.php](/code/files/api/tasks/recurrence_preview.php.md), [api/tasks/save_recurrence.php](/code/files/api/tasks/save_recurrence.php.md), [cron/task_recurrence.php](/code/files/cron/task_recurrence.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

