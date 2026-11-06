# includes/projects/benefits.php

> PHP - 204 lines

Projects - benefits realisation (3.3.0): what a project is meant to improve, measured from a baseline towards a target, and reviewed on a date.

A BENEFIT says what improves (title), how it is measured (measure, unit),
which way is better (direction up / down), where it started (baseline),
where it should get to (target, by target_date), who owns it and when it is
next reviewed. MEASUREMENTS are dated values; the latest by date is "now".

Its state is worked out, never stored (the same rule as health):
  closed        - somebody stopped reviewing it (status = closed)
  not_measured  - no measurement yet
  achieved      - the latest measurement reaches the target, in its direction
  missed        - the target date has passed without reaching it
  in_progress   - otherwise

─────────────────────────────────────────────────────────────────────────────
🔑 BENEFITS OUTLIVE THE PROJECT. Most arrive after it closes, so reviews are
found by `projectAlertsBenefits()` for EVERY project that is not cancelled -
unlike the other alerts, which only look at live ones.
  project.benefit_review_due  once per benefit per review date (the ledger,
                              fingerprint = the date), to its owner and the
                              project manager (project_benefit_notify).
Recording a measurement on or after (review date - 14 days) moves the next
review on by the benefit's review_months; 0 / NULL means no repeat.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectBenefitsReady()](/code/functions/projectBenefitsReady.md) | 34 | Has Database Verification created the tables? |
| [projectBenefitState()](/code/functions/projectBenefitState.md) | 51 | The worked-out state and progress of one benefit, given its latest value. |
| [projectBenefits()](/code/functions/projectBenefits.md) | 75 | One project's benefits with their measurements, state and progress - for its Benefits tab. |
| [projectBenefitStats()](/code/functions/projectBenefitStats.md) | 118 | Per project: benefits open and reviews due - for the Overview, the card and the assistant. |
| [projectBenefitNextReview()](/code/functions/projectBenefitNextReview.md) | 137 | The next review after `$from`, `$months` on; null when it does not repeat. |
| [projectBenefitNotifyIds()](/code/functions/projectBenefitNotifyIds.md) | 151 | Who is reminded about a benefit's review (project_benefit_notify). |
| [projectAlertsBenefits()](/code/functions/projectAlertsBenefits.md) | 162 | Reviews that have fallen due -> project.benefit_review_due, once per benefit per review date. Every project that is not cancelled: benefits outlive it. Called from `projectAlertsScan()`; quiet before Database Verification. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `projectBenefitsReady` | read [project_benefits](/code/tables/project_benefits.md) |  |
| 38 | `projectBenefitsReady` | read [project_benefit_measures](/code/tables/project_benefit_measures.md) |  |
| 78 | `projectBenefits` | read [project_benefits](/code/tables/project_benefits.md), read [analysts](/code/tables/analysts.md) |  |
| 85 | `projectBenefits` | read [project_benefit_measures](/code/tables/project_benefit_measures.md), read [analysts](/code/tables/analysts.md) |  |
| 123 | `projectBenefitStats` | read [project_benefits](/code/tables/project_benefits.md) |  |
| 166 | `projectAlertsBenefits` | read [project_benefits](/code/tables/project_benefits.md), read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 179 | `projectAlertsBenefits` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |

## How it hangs together

**Includes:** [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md)

**Included by:** [api/projects/get.php](/code/files/api/projects/get.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

**Calls:** [projectAlertsAsSystem()](/code/functions/projectAlertsAsSystem.md), [projectDispatch()](/code/functions/projectDispatch.md), [projectEventPayload()](/code/functions/projectEventPayload.md), [projectSetting()](/code/functions/projectSetting.md)

## Traps

> [!WARNING]
> **Line 188, in `projectAlertsBenefits`:** a review falling due is nobody's action. This scan also runs inside somebody's request (afterChange), and the bell never tells you about your own action - so it is dispatched as the system, or the project manager who happened to close the project would never hear that a review is due.

