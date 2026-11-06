# includes/projects/nudges.php

> PHP - 199 lines

Projects - the overdue digest and stalled-approval nudges (3.3.0). Called by `projectAlertsScan()` (includes/projects/alerts.php), so cron or the portfolio fallback runs them; both use the workflow_scheduled_emissions ledger so a period or a nudge fires once, however often the scan runs.

project.tasks_overdue     a digest to the PROJECT MANAGER of the project's
                            overdue tasks - project_overdue_digest: weekly (the
                            first scan of each ISO week, so Monday morning with
                            cron), daily, or off. Nothing overdue, nothing sent.
  project.approval_stalled  something has waited project_nudge_days days for a
                            decision (0 = off), and again every as many days:
                              proposal       a proposal pending approval -> its approvers
                              change_request a change request still open -> whoever may decide it
                              signoff        a gate sign-off nobody has given -> the person named
                              report         a report still a draft -> the project manager
                            Only things waiting 45 days or less: on the day this
                            arrives, months-old leftovers do not all ring at once.

Both are nobody's action, so they go out as the system (see the TRAP in
alerts.php), and both name their audience in notify_ids.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectAlertsOverdueDigest()](/code/functions/projectAlertsOverdueDigest.md) | 31 | The overdue-task digests. `$projectId` = one project; null = every live one. |
| [projectAlertsStalled()](/code/functions/projectAlertsStalled.md) | 80 | The nudges. `$projectId` = one project; null = all of them. |
| [projectNudgeProjects()](/code/functions/projectNudgeProjects.md) | 172 | Project rows shaped for `projectEventPayload()`, by id. |
| [projectNudgeDeciders()](/code/functions/projectNudgeDeciders.md) | 191 | Everybody who may decide a change request on this project - the setting's rule, applied to each active analyst. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `projectAlertsOverdueDigest` | read [suppliers](/code/tables/suppliers.md) |  |
| 39 | `projectAlertsOverdueDigest` | read [tasks](/code/tables/tasks.md), read [projects](/code/tables/projects.md), read [task_statuses](/code/tables/task_statuses.md), read [analysts](/code/tables/analysts.md) |  |
| 54 | `projectAlertsOverdueDigest` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |
| 92 | `projectAlertsStalled` | read [projects](/code/tables/projects.md) |  |
| 104 | `projectAlertsStalled` | read [project_change_requests](/code/tables/project_change_requests.md), read [projects](/code/tables/projects.md) |  |
| 119 | `projectAlertsStalled` | read [project_gate_items](/code/tables/project_gate_items.md), read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md) |  |
| 130 | `projectAlertsStalled` | read [project_reports](/code/tables/project_reports.md), read [projects](/code/tables/projects.md) |  |
| 139 | `projectAlertsStalled` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |
| 178 | `projectNudgeProjects` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 194 | `projectNudgeDeciders` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), [includes/projects/control.php](/code/files/includes/projects/control.php.md)

**Included by:** [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md)

**Calls:** [projectAlertsAsSystem()](/code/functions/projectAlertsAsSystem.md), [projectCanDecideChange()](/code/functions/projectCanDecideChange.md), [projectControlReady()](/code/functions/projectControlReady.md), [projectDispatch()](/code/functions/projectDispatch.md), [projectEventPayload()](/code/functions/projectEventPayload.md), [projectIntakeReady()](/code/functions/projectIntakeReady.md), [projectProposalApprovers()](/code/functions/projectProposalApprovers.md), [projectSetting()](/code/functions/projectSetting.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md), [tasksSupplierNameSql()](/code/functions/tasksSupplierNameSql.md)

