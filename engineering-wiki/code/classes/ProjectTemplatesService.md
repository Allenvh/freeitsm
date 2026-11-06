# ProjectTemplatesService

> Class in [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md), line 27

## Methods

| Method | What it does |
|---|---|
| [createFromTemplate()](#createfromtemplate) | Create a project from template `$key`. `$in` is the new-project form (name, start_date, ...): anything it sets wins over the template. Returns the id. |
| [cleanup()](#cleanup) | Remove a half-built project and the tasks made for it. |
| [saveFromProject()](#savefromproject) | Save a project as a template. `$parts`: plan, scope, raid, benefits (3.3.0), tolerances, targets. Needs the Templates capability - templates are shared by every company on the install. |
| [update()](#update) | Changes a project template's name, description and active flag. |
| [delete()](#delete) | Deletes a project template; projects made from it are untouched. |
| [setBuiltinHidden()](#setbuiltinhidden) | Hide or show a built-in template in the new-project picker. |
| [assertManage()](#assertmanage) | Throws unless the actor holds the Projects templates capability; system callers (actor 0) pass. |
| [nameDesc()](#namedesc) | A validated template name and optional description. |
| [row()](#row) | Loads one project template; throws not found when it does not exist. |

### createFromTemplate

*public static* - line 37

Create a project from template `$key`. `$in` is the new-project form (name, start_date, ...): anything it sets wins over the template. Returns the id.

```php
function createFromTemplate(
    PDO $conn,
    ActorContext $ctx,
    string $key,
    array $in,
    ?int $tenantId
): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | which template: 'builtin:<key>' or 'saved:<project_templates.id>' |
| `$in` | `array` |  | the new-project form (name, start_date, target_end_date, methodology...) - anything set wins over the template; an empty start_date means today |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Database**

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

**Called from:** [api/projects/save.php](/code/files/api/projects/save.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md)

### cleanup

*private static* - line 159

Remove a half-built project and the tasks made for it.

```php
function cleanup(PDO $conn, int $pid, array $taskIds): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$pid` | `int` |  | the half-built project to remove |
| `$taskIds` | `array` |  | the ids of the tasks already created for it |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 164 | `ProjectTemplatesService::cleanup` | **delete** [tasks](/code/tables/tasks.md) |  |
| 166 | `ProjectTemplatesService::cleanup` | **delete** [project_raid_tasks](/code/tables/project_raid_tasks.md), read [project_raid](/code/tables/project_raid.md) |  |
| 170 | `ProjectTemplatesService::cleanup` | **delete** [projects](/code/tables/projects.md) |  |

### saveFromProject

*public static* - line 189

Save a project as a template. `$parts`: plan, scope, raid, benefits (3.3.0), tolerances, targets. Needs the Templates capability - templates are shared by every company on the install.

With `$in`['id'] it REPLACES that saved template's plan instead - the way a
template is edited: start a project from it, change the project, save it
back. The template keeps its id and whether it is offered; projects
already started from it are untouched. Built-ins have no id, so they can
only be copied, never overwritten.

```php
function saveFromProject(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | name (required), description, parts (any of plan, scope, raid, benefits, tolerances, targets), id (a saved template to replace instead of adding a new one) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 200 | `ProjectTemplatesService::saveFromProject` | **update** [project_templates](/code/tables/project_templates.md) (`name`, `description`, `content`, `updated_datetime`) |  |
| 204 | `ProjectTemplatesService::saveFromProject` | **insert** [project_templates](/code/tables/project_templates.md) (`name`, `description`, `content`, `is_active`, `created_by_analyst_id`, `created_datetime`, `updated_datetime`) |  |

**Called from:** [api/projects/templates.php](/code/files/api/projects/templates.php.md)

### update

*public static* - line 219

Changes a project template's name, description and active flag.

```php
function update(PDO $conn, ActorContext $ctx, int $id, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the saved template (project_templates.id) |
| `$in` | `array` |  | name (required), description, is_active |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 224 | `ProjectTemplatesService::update` | **update** [project_templates](/code/tables/project_templates.md) (`name`, `description`, `is_active`, `updated_datetime`) |  |

**Called from:** [api/projects/templates.php](/code/files/api/projects/templates.php.md)

### delete

*public static* - line 233

Deletes a project template; projects made from it are untouched.

```php
function delete(PDO $conn, ActorContext $ctx, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the saved template (project_templates.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 238 | `ProjectTemplatesService::delete` | **delete** [project_templates](/code/tables/project_templates.md) |  |

**Called from:** [api/projects/templates.php](/code/files/api/projects/templates.php.md)

### setBuiltinHidden

*public static* - line 247

Hide or show a built-in template in the new-project picker.

```php
function setBuiltinHidden(PDO $conn, ActorContext $ctx, string $key, bool $hidden): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the built-in template's key, without the 'builtin:' prefix |
| `$hidden` | `bool` |  | true to hide it from the new-project picker, false to show it |

**Called from:** [api/projects/templates.php](/code/files/api/projects/templates.php.md)

### assertManage

*private static* - line 258

Throws unless the actor holds the Projects templates capability; system callers (actor 0) pass.

```php
function assertManage(PDO $conn, ActorContext $ctx): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |

### nameDesc

*private static* - line 271

A validated template name and optional description.

```php
function nameDesc(array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$in` | `array` |  | name (required, up to 150) and description (optional, cut to 500) |

### row

*private static* - line 285

Loads one project template; throws not found when it does not exist.

```php
function row(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the saved template (project_templates.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 288 | `ProjectTemplatesService::row` | read [project_templates](/code/tables/project_templates.md) |  |

