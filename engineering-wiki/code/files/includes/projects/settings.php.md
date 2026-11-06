# includes/projects/settings.php

> PHP - 484 lines

Projects - settings: the keys, their defaults, the one reader, and the permission rules they drive (3.2.0).

Every key lives in `system_settings` and is written only by
api/projects/settings.php, which validates it and checks the capability of
the tab it belongs to (projects/settings/manifest.php explains why the
generic settings writer may not touch these).

🔑 THE DEFAULTS ARE THE BEHAVIOUR PHASE 1 SHIPPED WITH, except one: changing a
project defaults to its TEAM (project manager, creator, members, Projects
managers) rather than everyone with the module - Ed's call, 2026-10-06, because
a project page is somebody's plan and the delete button sat on it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectSettingDefinitions()](/code/functions/projectSettingDefinitions.md) | 23 | key => [default, validator, tab]. |
| [projectSettings()](/code/functions/projectSettings.md) | 155 | Every setting with its default applied. Cached; `$fresh` after a save. |
| [projectSetting()](/code/functions/projectSetting.md) | 180 | One Projects setting as a string; '' when it is not set. |
| [projectSettingWrite()](/code/functions/projectSettingWrite.md) | 191 | Saves one Projects setting to system_settings (insert or update). |
| [projectSettingValidate()](/code/functions/projectSettingValidate.md) | 206 | Validate one incoming value; throws InvalidArgumentException with a message fit to show. |
| [projectScaleDefaults()](/code/functions/projectScaleDefaults.md) | 364 | The five default words for a scale ('probability' or 'impact') in the viewer's language. They live in lang/<locale>/projects.php, not in the setting, so an install that never changed them reads them in German for a German analyst. |
| [projectScaleParse()](/code/functions/projectScaleParse.md) | 382 | Read a stored scale. A JSON array is the format; a comma-separated string is what the first 3.2.0 builds wrote, read the same way so nothing needs migrating (it is rewritten as JSON the next time the tab is saved). |
| [projectScaleSize()](/code/functions/projectScaleSize.md) | 398 | How many steps a scale has: five for the RAID scales, four for priority (3.3.0). |
| [projectScaleLabels()](/code/functions/projectScaleLabels.md) | 408 | The words to show for a scale: the saved ones, or the translated defaults. |
| [projectIsManager()](/code/functions/projectIsManager.md) | 419 | Holds Manage Projects (admins always do - analystHasCapability short-circuits is_admin). |
| [projectCanCreate()](/code/functions/projectCanCreate.md) | 434 | True when the analyst may create projects - anyone, if the create policy says so, else project managers only. |
| [projectIsTeam()](/code/functions/projectIsTeam.md) | 446 | Is this analyst on the project's team: its manager, its creator or a member? (Members arrive with project_members; until that table exists the team is the manager and the creator.) |
| [projectCanChange()](/code/functions/projectCanChange.md) | 467 | True when the analyst may change the project - anyone, if the change policy says so, else its team and project managers. |
| [projectCanDelete()](/code/functions/projectCanDelete.md) | 478 | Deleting is never "anyone": the project manager, its creator, or Manage Projects. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 164 | `projectSettings` | read [system_settings](/code/tables/system_settings.md) |  |
| 194 | `projectSettingWrite` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |
| 451 | `projectIsTeam` | read [project_members](/code/tables/project_members.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |

## How it hangs together

**Includes:** [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Included by:** [api/projects/export.php](/code/files/api/projects/export.php.md), [api/projects/list.php](/code/files/api/projects/list.php.md), [api/projects/settings.php](/code/files/api/projects/settings.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md), [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md), [includes/projects/control.php](/code/files/includes/projects/control.php.md), [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md), [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [projectMethodologies()](/code/functions/projectMethodologies.md), [t()](/code/functions/t.md)

