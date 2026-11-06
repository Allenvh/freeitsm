# includes/projects/control.php

> PHP - 368 lines

Projects - change control (3.3.0): baselines and change requests.

A BASELINE is the plan as it stood when it was agreed - start and target
finish, the planned budget, the work (tasks and estimated hours), the Must
scope, and in detail every stage, milestone, Must item and budget line. It is
never edited: a new one is taken instead, numbered 1, 2, 3. Comparing the
latest with the plan as it is NOW shows the drift, item by item.

A CHANGE REQUEST asks to change the agreed plan and says what it does to time
(days, + later), cost (+ more, - a saving) and scope (words). It is proposed,
then approved or rejected by somebody allowed to (a setting), or withdrawn by
whoever raised it. It is never deleted - the requests ARE the audit trail of
why the plan moved.

─────────────────────────────────────────────────────────────────────────────
THE JUDGEMENT CALLS ARE SETTINGS (Ed's rule: "where different people will
want it to work slightly differently, make it configurable"):
  project_baseline_auto     off | start | stage - take one by itself when the
                            project goes active, or then AND each stage start
  project_change_approver   team | owner | managers - who may decide
  project_change_self       may the person who raised a request decide it
  project_change_apply      baseline | plan - approving only takes a new
                            baseline, or ALSO moves the target finish by the
                            days and adds the cost as a budget line
🔑 Approving ALWAYS takes a new baseline: that is what "approved" means - the
changed plan becomes the agreed one. Rejecting changes nothing.
TRAP: the variance is worked out against the plan NOW, so a project manager
who moved the target date by hand before the request was approved sees the
drift on the old baseline straight away - which is the point.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectControlReady()](/code/functions/projectControlReady.md) | 39 | Has Database Verification created the tables? Everything here is quiet before it. |
| [projectPlanSnapshot()](/code/functions/projectPlanSnapshot.md) | 54 | The plan as it is now, in the shape a baseline stores: the headline figures plus the detail, so the same function makes a baseline and the "now" side of every comparison. |
| [projectTakeBaseline()](/code/functions/projectTakeBaseline.md) | 120 | Take a baseline of the plan as it is now. Returns its id. |
| [projectDaysBetween()](/code/functions/projectDaysBetween.md) | 140 | Whole days from `$a` to `$b` (both Y-m-d); null if either is missing. |
| [projectBaselineVariance()](/code/functions/projectBaselineVariance.md) | 155 | How the plan NOW differs from a baseline. Headline figures, then the detail by item: moved (days), added (in the plan now, not in the baseline) and removed (in the baseline, gone now). Items are matched by id, so a renamed milestone is the same milestone. |
| [projectVarianceItems()](/code/functions/projectVarianceItems.md) | 198 | Dated items compared by id on one date column: [{name, kind: moved\|added\|removed, from, to, days}]. |
| [projectBaselineShape()](/code/functions/projectBaselineShape.md) | 217 | A stored baseline row in the snapshot shape. |
| [projectCanDecideChange()](/code/functions/projectCanDecideChange.md) | 238 | May this analyst approve or reject a change request? The setting says who (team = anyone who may change the project; owner = its project manager or Manage Projects; managers = Manage Projects only), and project_change_self whether the person who raised it may decide it too. |
| [projectBaselineAuto()](/code/functions/projectBaselineAuto.md) | 259 | Take a baseline by itself when the setting says so - never twice for the same stage, and once only for the project starting. Quiet: it must never break the change that triggered it. `$trigger` 'start' - the project went active; 'stage' - `$stageId` started |
| [projectChangeStats()](/code/functions/projectChangeStats.md) | 293 | Per project: change requests waiting for a decision - for the Overview, the card and the assistant. |
| [projectControlDetail()](/code/functions/projectControlDetail.md) | 310 | Everything the Change control tab shows. null before Database Verification. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 | `projectControlReady` | read [project_baselines](/code/tables/project_baselines.md) |  |
| 43 | `projectControlReady` | read [project_change_requests](/code/tables/project_change_requests.md) |  |
| 56 | `projectPlanSnapshot` | read [projects](/code/tables/projects.md) |  |
| 60 | `projectPlanSnapshot` | read [tasks](/code/tables/tasks.md) |  |
| 63 | `projectPlanSnapshot` | read [tasks](/code/tables/tasks.md) |  |
| 67 | `projectPlanSnapshot` | read [project_stages](/code/tables/project_stages.md) |  |
| 73 | `projectPlanSnapshot` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 80 | `projectPlanSnapshot` | read [project_items](/code/tables/project_items.md) |  |
| 87 | `projectPlanSnapshot` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 123 | `projectTakeBaseline` | read [project_baselines](/code/tables/project_baselines.md) |  |
| 124 | `projectTakeBaseline` | **insert** [project_baselines](/code/tables/project_baselines.md) (`project_id`, `number`, `label`, `reason`, `stage_id`, `change_request_id`, `start_date`, `target_end_date`, `budget_planned`, `currency`, `task_count`, `estimate_hours`) |  |
| 265 | `projectBaselineAuto` | read [projects](/code/tables/projects.md) |  |
| 270 | `projectBaselineAuto` | read [project_baselines](/code/tables/project_baselines.md) |  |
| 274 | `projectBaselineAuto` | read [project_baselines](/code/tables/project_baselines.md) |  |
| 280 | `projectBaselineAuto` | read [project_baselines](/code/tables/project_baselines.md) |  |
| 298 | `projectChangeStats` | read [project_change_requests](/code/tables/project_change_requests.md) |  |
| 315 | `projectControlDetail` | read [project_baselines](/code/tables/project_baselines.md), read [analysts](/code/tables/analysts.md), read [project_stages](/code/tables/project_stages.md), read [project_change_requests](/code/tables/project_change_requests.md) |  |
| 333 | `projectControlDetail` | read [project_change_requests](/code/tables/project_change_requests.md), read [analysts](/code/tables/analysts.md), read [project_baselines](/code/tables/project_baselines.md) |  |

## How it hangs together

**Includes:** [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md)

**Included by:** [api/projects/get.php](/code/files/api/projects/get.php.md), [includes/demo_data.php](/code/files/includes/demo_data.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

**Calls:** [projectCanChange()](/code/functions/projectCanChange.md), [projectEnabledTools()](/code/functions/projectEnabledTools.md), [projectIsManager()](/code/functions/projectIsManager.md), [projectSetting()](/code/functions/projectSetting.md)

