# includes/projects/alerts.php

> PHP - 377 lines

Projects - events and alerts (3.2.0): what a project tells Workflows, webhooks and the notification bell.

Every event goes through WorkflowEngine::dispatch(), the one funnel: a
workflow can act on it, a webhook can carry it ("post to Teams when a project
goes red"), and includes/notifications_router.php turns the ones listed in
NotificationsService::types() into a bell for the PROJECT MANAGER.

  project.created / .updated / .deleted   the write paths (ProjectsService)
  project.stage_closed     a stage, phase or sprint closed - by its gate or by hand
  project.health_changed   the health a project SHOWS moved (auto or by hand)
  project.tolerance_breached  a time or risk tolerance was exceeded
  project.stage_due        a stage ends in 7 days, then tomorrow (time-based)
  project.milestone_due    a milestone is 7 days away, then tomorrow (3.3.0, time-based)
  project.milestone_missed a milestone's date passed without it being reached (3.3.0)
  project.milestone_reached somebody marked a milestone reached (3.3.0, the write path)
  project.tasks_overdue    a digest of overdue tasks to the project manager (3.3.0, nudges.php)
  project.approval_stalled something waited too long for a decision (3.3.0, nudges.php)
  project.report_drafted   a scheduled report draft is ready to check (3.3.0, project_reports.php)

─────────────────────────────────────────────────────────────────────────────
WORKED OUT, NEVER STORED - SO SOMETHING HAS TO LOOK

Health and exceptions are computed on read (includes/projects/read.php). A
task going overdue overnight turns a project amber without anybody saving
anything, so there is no write path to dispatch from. `projectAlertsScan()`
looks, and compares with what it saw last time:

  projects.alert_health      the health last seen
  projects.alert_exceptions  the breaches last seen ("risk,time")

🔑 An event fires on a CHANGE only, and a breach re-arms once it clears - a
project that stays red is not news every hour. The write is compare-and-set
(WHERE alert_health <=> what we read), so two overlapping scans cannot both
fire. A NULL alert_health means never scanned: that first scan only records,
so upgrading does not ring every project manager's bell at once.

Stage ends use the workflow_scheduled_emissions ledger (the contract-expiry
one) with the end date as the fingerprint, so moving a stage's end re-arms
it. Unlike `workflowEmitOnce()` it records even when no workflow listens: the
bell is always listening.

─────────────────────────────────────────────────────────────────────────────
WHO RUNS THE SCAN

  cron/workflow_scheduled.php   the reliable path (workflowScheduledRun)
  api/projects/list.php         opening the portfolio, at most every 15 minutes,
                                for installs with no cron
  ProjectsService writes        one project, straight after a change to it

TRAP: the bell never tells you about your OWN action, and decides "own" from
the session. A scan started by somebody opening the portfolio is not their
action - if it ran as them, a project manager who opens Projects would never
hear that their project went red. Scans run through `projectAlertsAsSystem()`,
which hides the session's analyst while it dispatches. A change somebody
makes by hand (setting health to red) runs as them, so they are not told
about what they just did.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectEventPayload()](/code/functions/projectEventPayload.md) | 70 | The project as every project event carries it. |
| [projectDispatch()](/code/functions/projectDispatch.md) | 93 | Dispatch, never throwing: an event must not break the change that caused it. |
| [projectAlertsAsSystem()](/code/functions/projectAlertsAsSystem.md) | 107 | Run `$fn` with nobody signed in, as cron does - see the TRAP above. |
| [projectAlertRows()](/code/functions/projectAlertRows.md) | 122 | Live projects decorated with their health and exceptions, plus what the scan saw last. |
| [projectAlertsScan()](/code/functions/projectAlertsScan.md) | 150 | Compare each live project with what was seen last, and fire what changed. `$projectId` = one project (after a change to it); null = all of them. |
| [projectStageDueWindows()](/code/functions/projectStageDueWindows.md) | 224 | The two warnings before a stage ends. |
| [projectAlertsStagesDue()](/code/functions/projectAlertsStagesDue.md) | 234 | Stages ending soon -> project.stage_due, once per window per end date. Only the nearest window fires: a stage first seen a day out gets "tomorrow", not a week's notice as well. |
| [projectAlertsRun()](/code/functions/projectAlertsRun.md) | 278 | The whole scan as nobody - what cron and the portfolio fallback call. |
| [projectAlertsOpportunistic()](/code/functions/projectAlertsOpportunistic.md) | 287 | For installs with no cron: opening the portfolio runs the scan, at most every 15 minutes. Never throws; the portfolio must load whatever happens here. |
| [projectMilestoneEventFields()](/code/functions/projectMilestoneEventFields.md) | 306 | A milestone as its events carry it (3.3.0). state: due \| missed \| done. |
| [projectAlertsMilestones()](/code/functions/projectAlertsMilestones.md) | 328 | Milestones (3.3.0), on the same ledger as stage ends: project.milestone_due 7 days before, then the day before (nearest window only) project.milestone_missed once, the first scan after its date passes without it being reached. Moving the date re-arms both - the fingerprint is the due date. A milestone that was already past when it was ADDED is not news to anybody, so "missed" only fires for dates in the last 14 days. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 131 | `projectAlertRows` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 154 | `projectAlertsScan` | read [projects](/code/tables/projects.md) |  |
| 168 | `projectAlertsScan` | **update** [projects](/code/tables/projects.md) (`alert_health`, `alert_exceptions`) |  |
| 203 | `projectAlertsScan` | read [projects](/code/tables/projects.md) |  |
| 238 | `projectAlertsStagesDue` | read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 253 | `projectAlertsStagesDue` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |
| 290 | `projectAlertsOpportunistic` | read [system_settings](/code/tables/system_settings.md) |  |
| 294 | `projectAlertsOpportunistic` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 334 | `projectAlertsMilestones` | read [project_milestones](/code/tables/project_milestones.md), read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 349 | `projectAlertsMilestones` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |

## How it hangs together

**Includes:** [includes/projects/read.php](/code/files/includes/projects/read.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/projects/flow.php](/code/files/includes/projects/flow.php.md), [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md), [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md)

**Included by:** [api/projects/list.php](/code/files/api/projects/list.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md), [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md)

**Calls:** [projectAlertsBenefits()](/code/functions/projectAlertsBenefits.md), [projectAlertsOverdueDigest()](/code/functions/projectAlertsOverdueDigest.md), [projectAlertsStalled()](/code/functions/projectAlertsStalled.md), [projectCode()](/code/functions/projectCode.md), [projectDecorate()](/code/functions/projectDecorate.md), [projectExceptionColumns()](/code/functions/projectExceptionColumns.md), [projectFlowSnapshot()](/code/functions/projectFlowSnapshot.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectMilestoneState()](/code/functions/projectMilestoneState.md), [projectPriorityColumn()](/code/functions/projectPriorityColumn.md), [projectTaskStats()](/code/functions/projectTaskStats.md), [tasksContractorReminders()](/code/functions/tasksContractorReminders.md)

