# includes/services/checklists.php

> PHP - 847 lines

ChecklistsService — the single home for the SOP-checklist rules that other modules have to obey.

Contributed as PR #141 by Santhosh Srinivasan; this layer was added during
integration so the one rule that reaches outside the module — "a ticket with
outstanding mandatory steps does not close" — is written once instead of
being re-stated by every caller that can close a ticket.

Follows the three service rules (wiki: Service-Layer-Architecture):
  1. method(PDO, ActorContext, …) — no superglobals
  2. no transport — returns data or throws ServiceError, never echoes
  3. typed errors — ServiceError(kind, code, message)

──────────────────────────────────────────────────────────────────────────
🔴 WHY THE GATE IS HERE AND NOT IN THE BROWSER

As contributed, the gate was an `alert()` in assets/js/inbox.js and nothing
else. The UI refused to close the ticket; the v1 REST API, bulk actions, the
workflow engine and one line of curl all closed it with every mandatory step
outstanding. For a feature whose entire purpose is ISO/SOP compliance, an
unenforced gate is worse than no gate — it reports control that isn't there.

⚠️ AND WHY IT CAN ALWAYS BE OVERRIDDEN.

assets/js/inbox.js already carries the house position, written for #83 and
sitting three lines below where the checklist gate was inserted:

    "closing a ticket that still has unfinished tasks WARNS, it never blocks
     … a warning that cannot be shown must not become a block that cannot be
     cleared."

That is right, and a hard block breaks it: the analyst who owned step 4 has
left, the step is obsolete, and the ticket is stuck forever with no way out
but SQL. So the rule is not "you cannot close this". It is:

    close it with the steps outstanding and the override is RECORDED —
    who closed it, when, and which steps were skipped.

Which is what a compliance feature should produce anyway. An auditor does not
want a system where the bad outcome was impossible; they want one where it is
attributable. Blocking hides the exception, recording it surfaces it.
──────────────────────────────────────────────────────────────────────────

## Classes

- [ChecklistsService](/code/classes/ChecklistsService.md) (class, line 50)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 | `ChecklistsService::hasAttachedChecklists` | read [ticket_checklists](/code/tables/ticket_checklists.md) |  |
| 80 | `ChecklistsService::outstandingMandatorySteps` | read [ticket_checklist_items](/code/tables/ticket_checklist_items.md), read [ticket_checklists](/code/tables/ticket_checklists.md) |  |
| 285 | `ChecklistsService::writeClosureNote` | **insert** [ticket_notes](/code/tables/ticket_notes.md) (`ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime`) |  |
| 290 | `ChecklistsService::writeClosureNote` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |
| 370 | `ChecklistsService::saveLookup` | **update** [checklist_templates](/code/tables/checklist_templates.md) (`category`) |  |
| 372 | `ChecklistsService::saveLookup` | **update** [checklist_template_items](/code/tables/checklist_template_items.md) (`suggested_role`) |  |
| 373 | `ChecklistsService::saveLookup` | **update** [ticket_checklist_items](/code/tables/ticket_checklist_items.md) (`suggested_role`) |  |
| 443 | `ChecklistsService::tasksEnabled` | read [system_settings](/code/tables/system_settings.md) |  |
| 455 | `ChecklistsService::taskTenantId` | read [tasks](/code/tables/tasks.md) |  |
| 472 | `ChecklistsService::taskChecklists` | read [task_checklists](/code/tables/task_checklists.md) |  |
| 477 | `ChecklistsService::taskChecklists` | read [task_checklist_items](/code/tables/task_checklist_items.md) |  |
| 499 | `ChecklistsService::templatesForTask` | read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 520 | `ChecklistsService::attachTemplateToTask` | read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 547 | `ChecklistsService::copyTemplateToTask` | **insert** [task_checklists](/code/tables/task_checklists.md) (`task_id`, `template_id`, `title`, `closure_mode`, `sort_order`, `created_by_id`, `created_datetime`) |  |
| 553 | `ChecklistsService::copyTemplateToTask` | read [checklist_template_items](/code/tables/checklist_template_items.md) |  |
| 556 | `ChecklistsService::copyTemplateToTask` | **insert** [task_checklist_items](/code/tables/task_checklist_items.md) (`task_checklist_id`, `title`, `suggested_role`, `is_mandatory`, `requires_input`, `input_placeholder`, `sort_order`) |  |
| 575 | `ChecklistsService::nextTaskChecklistOrder` | read [task_checklists](/code/tables/task_checklists.md) |  |
| 589 | `ChecklistsService::reorderTaskChecklists` | **update** [task_checklists](/code/tables/task_checklists.md) (`sort_order`) |  |
| 603 | `ChecklistsService::reorderTaskChecklistItems` | **update** [task_checklist_items](/code/tables/task_checklist_items.md) (`sort_order`) |  |
| 616 | `ChecklistsService::taskIdForItem` | read [task_checklist_items](/code/tables/task_checklist_items.md), read [task_checklists](/code/tables/task_checklists.md) |  |
| 628 | `ChecklistsService::taskIdForChecklist` | read [task_checklists](/code/tables/task_checklists.md) |  |
| 646 | `ChecklistsService::toggleTaskItem` | read [task_checklist_items](/code/tables/task_checklist_items.md) |  |
| 659 | `ChecklistsService::toggleTaskItem` | **update** [task_checklist_items](/code/tables/task_checklist_items.md) (`is_completed`, `response_value`, `completed_by_id`, `completed_by_name`, `completed_datetime`) |  |
| 666 | `ChecklistsService::toggleTaskItem` | **update** [task_checklist_items](/code/tables/task_checklist_items.md) (`is_completed`, `response_value`, `completed_by_id`, `completed_by_name`, `completed_datetime`) |  |
| 672 | `ChecklistsService::toggleTaskItem` | read [task_checklist_items](/code/tables/task_checklist_items.md) |  |
| 688 | `ChecklistsService::removeTaskChecklist` | **delete** [task_checklist_items](/code/tables/task_checklist_items.md) |  |
| 689 | `ChecklistsService::removeTaskChecklist` | **delete** [task_checklists](/code/tables/task_checklists.md) |  |
| 710 | `ChecklistsService::deleteForTasks` | **delete** [task_checklist_items](/code/tables/task_checklist_items.md), read [task_checklists](/code/tables/task_checklists.md) |  |
| 713 | `ChecklistsService::deleteForTasks` | **delete** [task_checklists](/code/tables/task_checklists.md) |  |
| 725 | `ChecklistsService::taskOutstandingMandatorySteps` | read [task_checklist_items](/code/tables/task_checklist_items.md), read [task_checklists](/code/tables/task_checklists.md) |  |
| 792 | `ChecklistsService::recordTaskCompletionOverride` | **insert** [task_audit](/code/tables/task_audit.md) (`task_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime`) |  |
| 818 | `ChecklistsService::copyTaskChecklists` | read [task_checklists](/code/tables/task_checklists.md), read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 830 | `ChecklistsService::copyTaskChecklists` | **insert** [task_checklists](/code/tables/task_checklists.md) (`task_id`, `template_id`, `title`, `closure_mode`, `sort_order`, `created_by_id`, `created_datetime`) |  |
| 835 | `ChecklistsService::copyTaskChecklists` | **insert** [task_checklist_items](/code/tables/task_checklist_items.md) (`task_checklist_id`, `title`, `suggested_role`, `is_mandatory`, `requires_input`, `input_placeholder`, `sort_order`) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md)

**Included by:** [api/checklists/delete_lookup.php](/code/files/api/checklists/delete_lookup.php.md), [api/checklists/save_lookup.php](/code/files/api/checklists/save_lookup.php.md), [api/tasks/checklists.php](/code/files/api/tasks/checklists.php.md), [api/tasks/reorder.php](/code/files/api/tasks/reorder.php.md), [api/tasks/toggle_subtask.php](/code/files/api/tasks/toggle_subtask.php.md), [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md), [checklists/edit/index.php](/code/files/checklists/edit/index.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md), [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [ticketChecklistClosureMode()](/code/functions/ticketChecklistClosureMode.md), [ticketChecklistEmptyClosureMode()](/code/functions/ticketChecklistEmptyClosureMode.md)

