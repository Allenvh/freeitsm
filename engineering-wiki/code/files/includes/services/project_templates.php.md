# includes/services/project_templates.php

> PHP - 295 lines

ProjectTemplatesService - start a project from a template, save a project as one, and look after the list (3.2.0).

The template format and the built-ins are in includes/projects/templates.php.

🔑 A PROJECT FROM A TEMPLATE IS BUILT THROUGH THE SAME DOORS AS ONE BUILT BY
HAND: `createProject()` (who may create, which company), and
`createTaskInProject()` for every task, so each is an ordinary task with its
events, search entry and company - not rows slipped into the table. Stages,
scope, RAID, tolerances and targets have no side effects and are written
directly, with ONE history line ("started from <template>") instead of forty.

No transaction: TasksService opens its own, and PDO cannot nest them. If
anything fails after the project exists, the half-built project and its tasks
are removed again (cleanup()), so a failure never leaves a stray project.

## Classes

- [ProjectTemplatesService](/code/classes/ProjectTemplatesService.md) (class, line 27)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `ProjectTemplatesService::createFromTemplate` | **insert** [project_stages](/code/tables/project_stages.md) (`project_id`, `kind`, `name`, `goal`, `start_date`, `end_date`, `position`, `status`, `created_datetime`, `updated_datetime`) |  |
| 81 | `ProjectTemplatesService::createFromTemplate` | **update** [project_stages](/code/tables/project_stages.md) (`gate_kind`) |  |
| 82 | `ProjectTemplatesService::createFromTemplate` | **insert** [project_gate_items](/code/tables/project_gate_items.md) (`project_id`, `stage_id`, `kind`, `title`, `position`, `created_by_id`, `created_datetime`) |  |
| 87 | `ProjectTemplatesService::createFromTemplate` | **insert** [project_milestones](/code/tables/project_milestones.md) (`project_id`, `stage_id`, `name`, `due_date`, `position`, `created_by_analyst_id`, `created_datetime`, `updated_datetime`) |  |
| 96 | `ProjectTemplatesService::createFromTemplate` | **insert** [task_dependencies](/code/tables/task_dependencies.md) (`task_id`, `depends_on_id`, `lag_days`, `created_by_id`, `created_datetime`) |  |
| 104 | `ProjectTemplatesService::createFromTemplate` | **insert** [project_benefits](/code/tables/project_benefits.md) (`project_id`, `title`, `measure`, `unit`, `direction`, `target_value`, `target_date`, `review_date`, `review_months`, `status`, `position`, `created_by_id`) |  |
| 114 | `ProjectTemplatesService::createFromTemplate` | **insert** [project_items](/code/tables/project_items.md) (`project_id`, `title`, `description`, `moscow`, `status`, `position`, `created_datetime`, `updated_datetime`) |  |
| 119 | `ProjectTemplatesService::createFromTemplate` | **insert** [project_raid](/code/tables/project_raid.md) (`project_id`, `type`, `title`, `description`, `probability`, `impact`, `response`, `response_plan`, `status`, `raised_by_id`, `raised_datetime`, `updated_datetime`) |  |
| 124 | `ProjectTemplatesService::createFromTemplate` | **insert** [project_tolerances](/code/tables/project_tolerances.md) (`project_id`, `stage_id`, `dimension`, `value`) |  |
| 131 | `ProjectTemplatesService::createFromTemplate` | **insert** [project_asset_targets](/code/tables/project_asset_targets.md) (`project_id`, `name`, `scope`, `scope_type_id`, `scope_field`, `scope_value`, `done_field`, `done_op`, `done_value`, `target_date`, `position`, `created_by_analyst_id`) |  |
| 164 | `ProjectTemplatesService::cleanup` | **delete** [tasks](/code/tables/tasks.md) |  |
| 166 | `ProjectTemplatesService::cleanup` | **delete** [project_raid_tasks](/code/tables/project_raid_tasks.md), read [project_raid](/code/tables/project_raid.md) |  |
| 170 | `ProjectTemplatesService::cleanup` | **delete** [projects](/code/tables/projects.md) |  |
| 200 | `ProjectTemplatesService::saveFromProject` | **update** [project_templates](/code/tables/project_templates.md) (`name`, `description`, `content`, `updated_datetime`) |  |
| 204 | `ProjectTemplatesService::saveFromProject` | **insert** [project_templates](/code/tables/project_templates.md) (`name`, `description`, `content`, `is_active`, `created_by_analyst_id`, `created_datetime`, `updated_datetime`) |  |
| 224 | `ProjectTemplatesService::update` | **update** [project_templates](/code/tables/project_templates.md) (`name`, `description`, `is_active`, `updated_datetime`) |  |
| 238 | `ProjectTemplatesService::delete` | **delete** [project_templates](/code/tables/project_templates.md) |  |
| 288 | `ProjectTemplatesService::row` | read [project_templates](/code/tables/project_templates.md) |  |

## How it hangs together

**Includes:** [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md), [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md), [includes/projects/dependencies.php](/code/files/includes/projects/dependencies.php.md), [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md)

**Included by:** [api/projects/save.php](/code/files/api/projects/save.php.md), [api/projects/templates.php](/code/files/api/projects/templates.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [projectBenefitsReady()](/code/functions/projectBenefitsReady.md), [projectBuiltinTemplates()](/code/functions/projectBuiltinTemplates.md), [projectDependenciesReady()](/code/functions/projectDependenciesReady.md), [projectGateItemsReady()](/code/functions/projectGateItemsReady.md), [projectMethodologies()](/code/functions/projectMethodologies.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectSetting()](/code/functions/projectSetting.md), [projectSettings()](/code/functions/projectSettings.md), [projectSettingWrite()](/code/functions/projectSettingWrite.md), [projectsPhase2Ready()](/code/functions/projectsPhase2Ready.md), [projectTargetNormalise()](/code/functions/projectTargetNormalise.md), [projectTargetOptions()](/code/functions/projectTargetOptions.md), [projectTargetsReady()](/code/functions/projectTargetsReady.md), [projectTemplateCapture()](/code/functions/projectTemplateCapture.md), [projectTemplateLoad()](/code/functions/projectTemplateLoad.md), [projectTemplatesReady()](/code/functions/projectTemplatesReady.md)

## Traps

> [!WARNING]
> **Line 49, in `ProjectTemplatesService::createFromTemplate`:** read the date as UTC midnight. strtotime("2026-10-07") is LOCAL midnight, which `gmdate()` below turns into the day before whenever the server is ahead of UTC (British Summer Time) - every stage and task a day early.

