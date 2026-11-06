# api/projects/settings.php

> API endpoint - 166 lines

Projects -> Settings, behind the SAME capability as each tab (3.2.0).

GET                                  every setting with its default, the roles
                                     list, and which tabs this analyst may write
POST {action:'save', tab, settings:{key: value}}
                                     validated per key; every key must belong
                                     to `tab`, and `tab`'s capability is checked
POST {action:'role_save', id?, name, description, is_active}     Roles tab
POST {action:'role_delete', id}                                  Roles tab
POST {action:'role_reorder', ids:[...]}                          Roles tab

## The endpoint

| | |
|---|---|
| URL | `api/projects/settings.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php); Signed-in analyst (session check) (via includes/i18n.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectRolesForSettings()](/code/functions/projectRolesForSettings.md) | 28 | The roles list with how many members hold each - for the Roles tab. |
| [projectRatesForSettings()](/code/functions/projectRatesForSettings.md) | 40 | Default and analyst rates with their dates, newest first - Budget tab only. |
| [projectSettingsForScreen()](/code/functions/projectSettingsForScreen.md) | 53 | Settings as the screen shows them: each risk scale as its five words. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 | `projectRolesForSettings` | read [project_members](/code/tables/project_members.md), read [project_roles](/code/tables/project_roles.md) |  |
| 43 | `projectRatesForSettings` | read [project_labour_rates](/code/tables/project_labour_rates.md), read [analysts](/code/tables/analysts.md) |  |
| 77 |  | read [analysts](/code/tables/analysts.md) |  |
| 94 |  | read [analysts](/code/tables/analysts.md) |  |
| 102 |  | **insert** [project_labour_rates](/code/tables/project_labour_rates.md) (`scope`, `ref_id`, `hourly_rate`, `effective_from`, `created_by_id`, `created_datetime`) |  |
| 105 |  | **delete** [project_labour_rates](/code/tables/project_labour_rates.md) |  |
| 120 |  | read [project_roles](/code/tables/project_roles.md) |  |
| 124 |  | **update** [project_roles](/code/tables/project_roles.md) (`name`, `description`, `is_active`) |  |
| 126 |  | read [project_roles](/code/tables/project_roles.md) |  |
| 127 |  | **insert** [project_roles](/code/tables/project_roles.md) (`name`, `description`, `display_order`, `is_active`) |  |
| 134 |  | **delete** [project_roles](/code/tables/project_roles.md) |  |
| 138 |  | **update** [project_roles](/code/tables/project_roles.md) (`display_order`) |  |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [projectApiBody()](/code/functions/projectApiBody.md), [projectApiFail()](/code/functions/projectApiFail.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectMoney()](/code/functions/projectMoney.md), [projectScaleDefaults()](/code/functions/projectScaleDefaults.md), [projectScaleLabels()](/code/functions/projectScaleLabels.md), [projectSettingDefinitions()](/code/functions/projectSettingDefinitions.md), [projectSettings()](/code/functions/projectSettings.md), [projectSettingValidate()](/code/functions/projectSettingValidate.md), [projectSettingWrite()](/code/functions/projectSettingWrite.md), [projectSyncCalendar()](/code/functions/projectSyncCalendar.md)

