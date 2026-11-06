# includes/task_contractors.php

> PHP - 223 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 10 Contractors](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-10-Contractors)

Contractors on tasks (3.3.0) - work given to a THIRD PARTY: a supplier from Contracts -> Suppliers, and optionally one of its contacts.

tasks.assigned_supplier_id   the supplier doing the work
  tasks.assigned_contact_id    the person there (a contacts row of that supplier)

Alongside the analyst and the team, not instead of them: the analyst on a
contractor's task is the person here who CHASES it. So capacity counts a
contractor's task against the contractor, never against that analyst
(includes/projects/capacity.php), and the overdue digest, health and the
project manager's view treat it as ordinary project work, marked as a contractor's.

The contractor has no login. What they hear is project_contractor_email
(Projects -> Settings -> General, OFF by default): when 'on', the contact is
emailed when given a task (tasksContractorEmail(..., 'assigned')), two days
before it is due and once the day after it is missed (tasksContractorReminders(),
run by the hourly scan), through the system mailbox - email log route
"Task for a contractor". Every task with a contractor, in a project or not.

Suppliers and contacts are install-wide (Contracts has no companies), so a task
in any company may name any supplier. CHOOSING one needs the Contracts module
(where suppliers live - api/tasks/contractors.php); SEEING the name on a task
you can already see does not, like a project or team name.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [tasksContractorReady()](/code/functions/tasksContractorReady.md) | 31 | Has Database Verification added the two columns? Before it, there are no contractors and no errors. |
| [tasksSupplierNameSql()](/code/functions/tasksSupplierNameSql.md) | 46 | The name a supplier goes by. |
| [tasksContractors()](/code/functions/tasksContractors.md) | 57 | The contractor on each of these tasks: [task_id => {supplier_id, supplier_name, contact_id, contact_name, contact_email}]. Tasks without one are absent. |
| [tasksWithContractors()](/code/functions/tasksWithContractors.md) | 79 | Add the contractor fields to task rows (each with an 'id'). |
| [tasksContractorValidate()](/code/functions/tasksContractorValidate.md) | 98 | Check a supplier / contact pair: the supplier exists; the contact (if any) works for it. A contact alone brings their supplier. Returns [supplierId, contactId]. |
| [tasksContractorChoices()](/code/functions/tasksContractorChoices.md) | 120 | Suppliers to choose from, each with its active contacts. |
| [tasksContractorEmailOn()](/code/functions/tasksContractorEmailOn.md) | 130 | Is project_contractor_email on? |
| [tasksContractorEmail()](/code/functions/tasksContractorEmail.md) | 147 | Email a task's contractor contact - `$kind` 'assigned', 'due' (due soon) or 'overdue'. Quiet: false when the setting is off, there is no contact email or the send fails (the email log says which). Never throws. |
| [tasksContractorReminders()](/code/functions/tasksContractorReminders.md) | 204 | The reminders, from the hourly scan (projectAlertsScan): two days before a contractor's task is due, and once the day after it is missed - each once per due date (the ledger), so moving the date re-arms them. Open tasks with a contact only; nothing when the setting is off. Returns how many were sent. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 | `tasksContractorReady` | read [tasks](/code/tables/tasks.md) |  |
| 63 | `tasksContractors` | read [tasks](/code/tables/tasks.md), read [suppliers](/code/tables/suppliers.md), read [contacts](/code/tables/contacts.md) |  |
| 103 | `tasksContractorValidate` | read [contacts](/code/tables/contacts.md) |  |
| 112 | `tasksContractorValidate` | read [suppliers](/code/tables/suppliers.md) |  |
| 122 | `tasksContractorChoices` | read [suppliers](/code/tables/suppliers.md) |  |
| 123 | `tasksContractorChoices` | read [contacts](/code/tables/contacts.md) |  |
| 152 | `tasksContractorEmail` | read [tasks](/code/tables/tasks.md), read [contacts](/code/tables/contacts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md) |  |
| 164 | `tasksContractorEmail` | read [projects](/code/tables/projects.md) |  |
| 207 | `tasksContractorReminders` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 213 | `tasksContractorReminders` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |

## How it hangs together

**Includes:** [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md)

**Included by:** [api/tasks/contractors.php](/code/files/api/tasks/contractors.php.md), [api/tasks/get.php](/code/files/api/tasks/get.php.md), [api/tasks/list.php](/code/files/api/tasks/list.php.md), [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md), [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md)

**Calls:** [projectSetting()](/code/functions/projectSetting.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md), [systemName()](/code/functions/systemName.md)

