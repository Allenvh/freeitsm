# includes/projects/templates.php

> PHP - 520 lines

Projects - templates (3.2.0): start a project already planned.

Used by api/projects/save.php (a new project with `template`),
api/projects/templates.php and api/projects/lookups.php (the picker).

A template is a plan with no dates and no people:
  methodology, colour, icon, goal, summary, duration_days,
  stages  [{name, goal, start_day, end_day, tasks: [{title, description?, due_day?, estimate_hours?}],
           gate_kind? (standard | golive), gate_items? [{kind: check|document|signoff|change, title}]}]  (3.3.0)
  tasks   [{title, description?, due_day?}]         - not in any stage
  milestones [{name, day, stage?}]                   - stage = index into stages, or null (3.3.0)
  items   [{title, description?, moscow?}]          - scope, MoSCoW
  raid    [{type, title, description?, probability?, impact?, response?, response_plan?}]  type incl. dependency (3.3.0)
  tolerances {time?, risk?, cost? (3.3.0)}
  targets [{name, scope, scope_field?, scope_value?, done_field, done_op, done_value}]
  tailoring {tool: bool}
  dependencies [{task, on, lag?}]  (3.3.0) - task waits for on to finish. Both are a task's place in the
               plan: every stage's tasks in order, then the loose tasks, counting from 0.
  benefits [{title, measure?, unit?, direction (up|down), target_value?, target_day?, review_months?}]  (3.3.0)
               - never a baseline (that is measured on the project) and never an owner.
Days count from the project's start (day 0), so the same template plans a
project starting next week or next spring.

🔑 NOTHING IN A TEMPLATE BELONGS TO A COMPANY OR A PERSON - no analysts, no
tickets, no asset ids. One template serves every company on an MSP install.
List values in a target's rule (a status, a location) are kept as NAMES and
looked up when the template is used; a name that does not exist there drops
that one target rather than guessing.

Two kinds:
  built-in  - written here (projectBuiltinTemplates), keyed "builtin:<key>".
              Never in the database, so deleting every template can never
              bring them back unasked; Projects -> Settings -> Templates can
              hide one (setting project_hidden_templates).
  saved     - project_templates rows, keyed "saved:<id>", made with
              "Save as template" on a project.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectGateKindReady()](/code/functions/projectGateKindReady.md) | 48 | Has Database Verification added project_stages.gate_kind (3.3.0)? |
| [projectTemplatesReady()](/code/functions/projectTemplatesReady.md) | 58 | True when the project_templates table exists (after Database Verification). Cached per request. |
| [projectBuiltinTemplates()](/code/functions/projectBuiltinTemplates.md) | 68 | The templates that ship with FreeITSM. English, like the seeded roles. |
| [projectTemplateNormalise()](/code/functions/projectTemplateNormalise.md) | 224 | Check and tidy template content (from a saved row, the screen or a built-in). Unknown keys are dropped, every list is capped, days are clamped to 0-3650. |
| [projectTemplateSummary()](/code/functions/projectTemplateSummary.md) | 337 | What the picker shows about a template: counts, without the whole plan. |
| [projectTemplateList()](/code/functions/projectTemplateList.md) | 357 | Every template. `$all` = false: only what the new-project picker offers (built-ins not hidden, saved ones active). true: for the Settings tab. |
| [projectTemplateLoad()](/code/functions/projectTemplateLoad.md) | 385 | [name, content] for a key, or null when there is no such (usable) template. |
| [projectTemplateCapture()](/code/functions/projectTemplateCapture.md) | 412 | A project as a template: its plan with dates turned into days from its start, and none of its people, companies or links. `$parts` picks what to keep: plan, scope, raid, tolerances, targets. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 | `projectGateKindReady` | read [project_stages](/code/tables/project_stages.md) |  |
| 62 | `projectTemplatesReady` | read [project_templates](/code/tables/project_templates.md) |  |
| 107 | `projectBuiltinTemplates` | read [assets](/code/tables/assets.md) |  |
| 367 | `projectTemplateList` | read [project_templates](/code/tables/project_templates.md), read [analysts](/code/tables/analysts.md) |  |
| 395 | `projectTemplateLoad` | read [project_templates](/code/tables/project_templates.md) |  |
| 418 | `projectTemplateCapture` | read [project_stages](/code/tables/project_stages.md) |  |
| 432 | `projectTemplateCapture` | read [project_stages](/code/tables/project_stages.md) |  |
| 434 | `projectTemplateCapture` | read [tasks](/code/tables/tasks.md) |  |
| 446 | `projectTemplateCapture` | read [project_gate_items](/code/tables/project_gate_items.md) |  |
| 464 | `projectTemplateCapture` | read [task_dependencies](/code/tables/task_dependencies.md) |  |
| 471 | `projectTemplateCapture` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 479 | `projectTemplateCapture` | read [project_items](/code/tables/project_items.md) |  |
| 487 | `projectTemplateCapture` | read [project_raid](/code/tables/project_raid.md) |  |
| 495 | `projectTemplateCapture` | read [project_benefits](/code/tables/project_benefits.md) |  |
| 508 | `projectTemplateCapture` | read [project_asset_targets](/code/tables/project_asset_targets.md) |  |

## How it hangs together

**Includes:** [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md), [includes/projects/dependencies.php](/code/files/includes/projects/dependencies.php.md), [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md), [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

**Included by:** [api/projects/lookups.php](/code/files/api/projects/lookups.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md)

**Calls:** [projectBenefitsReady()](/code/functions/projectBenefitsReady.md), [projectColours()](/code/functions/projectColours.md), [projectDependenciesReady()](/code/functions/projectDependenciesReady.md), [projectEstimatesReady()](/code/functions/projectEstimatesReady.md), [projectGateItemsReady()](/code/functions/projectGateItemsReady.md), [projectIcons()](/code/functions/projectIcons.md), [projectMethodologies()](/code/functions/projectMethodologies.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectSetting()](/code/functions/projectSetting.md), [projectTargetDoneFields()](/code/functions/projectTargetDoneFields.md), [projectTargetScopeFields()](/code/functions/projectTargetScopeFields.md), [projectTargetsReady()](/code/functions/projectTargetsReady.md), [projectTargetValueNames()](/code/functions/projectTargetValueNames.md), [projectToolDefinitions()](/code/functions/projectToolDefinitions.md)

