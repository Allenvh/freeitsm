# ProjectsService

> Class in [includes/services/projects.php](/code/files/includes/services/projects.php.md), line 41

## Methods

| Method | What it does |
|---|---|
| [fieldMap()](#fieldmap) | The fields a person (or an API key) may set, and how each is validated. |
| [saveProject()](#saveproject) | Create (no id) or update (id present). Returns ['id', 'created']. |
| [createProject()](#createproject) | Creates a project for a company and returns its id. |
| [updateProject()](#updateproject) | Changes a project's fields, records each change in its history, refreshes the calendar, fires project.updated and checks health and tolerances. |
| [deleteProject()](#deleteproject) | Delete a project. Its tasks are DETACHED, never deleted; its stages and history go with it. Returns ['id', 'tasks_detached']. |
| [applyMethodology()](#applymethodology) | Relabel the OPEN time boxes to the preset's kind. Closed ones are history and keep the name they were run under. |
| [saveStage()](#savestage) | Create or update a time box. Returns its id. |
| [deleteStage()](#deletestage) | Delete a time box. Its tasks stay in the project, unassigned to any stage. |
| [reorderStages()](#reorderstages) | Put the project's time boxes in the given order (ids not listed keep their place after). |
| [assignTask()](#assigntask) | Put a task in a project (and optionally a stage), or take it out (null). The task and the project must belong to the same company - a project is one company's work, and a task cannot carry another company's plan. |
| [createTaskInProject()](#createtaskinproject) | Create a task straight into a project (and optionally a stage). |
| [loadRow()](#loadrow) | Loads one project; throws not found when it does not exist. |
| [loadForActor()](#loadforactor) | Load a project the caller may touch - out of scope reads as not found. |
| [loadStage()](#loadstage) | Loads one project stage; throws not found when it does not exist. |
| [assertCanChange()](#assertcanchange) | May this caller change this project? Projects -> Settings -> General decides (its team, or everyone with the module); people who manage Projects always may. A system actor (actorId 0 - demo import, scheduled work) is not a person and is not asked. |
| [assertScope()](#assertscope) | Throws not found with `$notFound` when the row's company is outside the actor's companies. |
| [sameTenant()](#sametenant) | True when two company ids are the same company, treating NULL as the Default company. |
| [storeTenant()](#storetenant) | The Default company is stored as NULL, the way every scoped table does. |
| [validateField()](#validatefield) | Validates and cleans one project field according to its type in `fieldMap()`, and returns the value to store. |
| [date()](#date) | Validates an optional YYYY-MM-DD date; blank gives null. |
| [assertDates()](#assertdates) | Throws when the end date is before the start date. |
| [str()](#str) | Trimmed text cut to `$max` characters, or null when blank. |
| [same()](#same) | True when two values are equal as strings; null only equals null. |
| [source()](#source) | The history source for the actor: 'api' or 'app'. |
| [syncCalendar()](#synccalendar) | Keep the shared Calendar in step (includes/projects/calendar.php). Call it AFTER the write, outside any transaction; it never throws. |
| [afterChange()](#afterchange) | After a change to one project: fire what its health and tolerances now say (includes/projects/alerts.php). Runs as the person who made the change, so the bell does not tell them about it. Never throws. |
| [stageClosed()](#stageclosed) | project.stage_closed - by its gate (`$decision`) or by hand (null). |
| [raidEscalated()](#raidescalated) | project.raid_escalated - a RAID entry needs somebody above the project manager (3.3.0). |
| [signoffEvent()](#signoffevent) | project.signoff_requested (3.3.0): somebody is named to sign off an item at a stage gate. |
| [decideProposal()](#decideproposal) | Approve or reject a proposal (3.3.0, includes/projects/intake.php). Approving records who and when and - when project_proposal_on_approve is 'active' - starts the project; rejecting cancels it. Either way it is audited and the proposer is told. Compare-and-set, so two approvers decide it once. |
| [proposalEvent()](#proposalevent) | project.proposal_submitted (to the approvers) / project.proposal_decided (to whoever proposed it, when that was an analyst) - 3.3.0. The payload carries the proposer's email so a workflow can tell somebody who asked on a form and has no bell. |
| [changeEvent()](#changeevent) | project.change_raised / project.change_decided (3.3.0) - a change request was raised, or approved or rejected. includes/projects/control.php. |
| [milestoneReached()](#milestonereached) | project.milestone_reached - somebody marked a milestone done (3.3.0). |
| [eventFor()](#eventfor) | The project as its events carry it, with the health it shows; null if it is gone. |
| [dispatch()](#dispatch) | Fires a project workflow event with the project's details. |
| [touchProject()](#touchproject) | Sets the project's updated time to now, for the other project services. |
| [touch()](#touch) | Sets the project's updated time to now. |
| [audit()](#audit) | Records one change in a project's history; a failure is logged, never thrown. |
| [auditDisplay()](#auditdisplay) | What the history shows for a value: names, not ids; a line, not a paragraph. |

### fieldMap

*public static* - line 44

The fields a person (or an API key) may set, and how each is validated.

```php
function fieldMap(): array
```

**Called from:** [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md)

### saveProject

*public static* - line 80

Create (no id) or update (id present). Returns ['id', 'created'].

```php
function saveProject(PDO $conn, ActorContext $ctx, array $in, ?int $tenantId = null): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the project fields from `fieldMap()`; with id it updates that project, without one it creates |
| `$tenantId` | `?int` | `null` | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [api/projects/save.php](/code/files/api/projects/save.php.md)

### createProject

*public static* - line 98

Creates a project for a company and returns its id.

Fills in the default method, visibility and project manager from Projects -> Settings
and the creator, starts it as proposed when proposals need approval, records it in
the history and fires project.created.

```php
function createProject(PDO $conn, ActorContext $ctx, array $in, ?int $tenantId): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the project fields from `fieldMap()` (name required); methodology, visibility and owner_analyst_id default from settings and the creator; _from_form marks a proposal made on a form |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 144 | `ProjectsService::createProject` | **insert** [projects](/code/tables/projects.md) |  |

**Called from:** [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md)

### updateProject

*public static* - line 169

Changes a project's fields, records each change in its history, refreshes the calendar, fires project.updated and checks health and tolerances.

Only the team or a project manager may change who can see it; a proposal waiting for
approval cannot be started.

```php
function updateProject(PDO $conn, ActorContext $ctx, int $id, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the project to change |
| `$in` | `array` |  | only the fields to change, keyed as in `fieldMap()`; unchanged values are skipped |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 217 | `ProjectsService::updateProject` | **update** [projects](/code/tables/projects.md) |  |

**Called from:** [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### deleteProject

*public static* - line 248

Delete a project. Its tasks are DETACHED, never deleted; its stages and history go with it. Returns ['id', 'tasks_detached'].

```php
function deleteProject(PDO $conn, ActorContext $ctx, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the project to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 257 | `ProjectsService::deleteProject` | **update** [tasks](/code/tables/tasks.md) (`project_id`, `project_stage_id`) |  |
| 264 | `ProjectsService::deleteProject` | **delete** [project_benefit_measures](/code/tables/project_benefit_measures.md), read [project_benefits](/code/tables/project_benefits.md) |  |
| 266 | `ProjectsService::deleteProject` | **delete** [project_raid_tasks](/code/tables/project_raid_tasks.md), read [project_raid](/code/tables/project_raid.md) |  |
| 280 | `ProjectsService::deleteProject` | **delete** [project_labour_rates](/code/tables/project_labour_rates.md) |  |
| 284 | `ProjectsService::deleteProject` | **update** [status_planned](/code/tables/status_planned.md) (`project_id`) |  |
| 285 | `ProjectsService::deleteProject` | **delete** [projects](/code/tables/projects.md) |  |

**Called from:** [api/projects/delete.php](/code/files/api/projects/delete.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md)

### applyMethodology

*private static* - line 305

Relabel the OPEN time boxes to the preset's kind. Closed ones are history and keep the name they were run under.

```php
function applyMethodology(PDO $conn, int $projectId, string $method): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$method` | `string` |  | the new methodology key from `projectMethodologies()`; an unknown key does nothing |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 309 | `ProjectsService::applyMethodology` | **update** [project_stages](/code/tables/project_stages.md) (`kind`, `updated_datetime`) |  |
| 315 | `ProjectsService::applyMethodology` | read [project_stages](/code/tables/project_stages.md) |  |
| 321 | `ProjectsService::applyMethodology` | **update** [project_stages](/code/tables/project_stages.md) (`status`) |  |

### saveStage

*public static* - line 336

Create or update a time box. Returns its id.

```php
function saveStage(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (a stage to change; absent for new), name (up to 150), goal, start_date, end_date, status - fields left out keep their value |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 359 | `ProjectsService::saveStage` | read [project_stages](/code/tables/project_stages.md) |  |
| 367 | `ProjectsService::saveStage` | **update** [project_stages](/code/tables/project_stages.md) (`name`, `goal`, `start_date`, `end_date`, `status`, `updated_datetime`) |  |
| 380 | `ProjectsService::saveStage` | read [project_stages](/code/tables/project_stages.md) |  |
| 381 | `ProjectsService::saveStage` | **insert** [project_stages](/code/tables/project_stages.md) (`project_id`, `kind`, `name`, `goal`, `start_date`, `end_date`, `position`, `status`, `created_datetime`, `updated_datetime`) |  |

**Called from:** [api/projects/stage_save.php](/code/files/api/projects/stage_save.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### deleteStage

*public static* - line 398

Delete a time box. Its tasks stay in the project, unassigned to any stage.

```php
function deleteStage(PDO $conn, ActorContext $ctx, int $projectId, int $stageId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$stageId` | `int` |  | the stage (project_stages.id) to delete; must belong to the project |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 405 | `ProjectsService::deleteStage` | **update** [tasks](/code/tables/tasks.md) (`project_stage_id`) |  |
| 407 | `ProjectsService::deleteStage` | **update** [project_milestones](/code/tables/project_milestones.md) (`stage_id`) |  |
| 409 | `ProjectsService::deleteStage` | **delete** [project_gate_items](/code/tables/project_gate_items.md) |  |
| 410 | `ProjectsService::deleteStage` | **delete** [project_stages](/code/tables/project_stages.md) |  |

**Called from:** [api/projects/stage_delete.php](/code/files/api/projects/stage_delete.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md)

### reorderStages

*public static* - line 427

Put the project's time boxes in the given order (ids not listed keep their place after).

```php
function reorderStages(PDO $conn, ActorContext $ctx, int $projectId, array $ids): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$ids` | `array` |  | stage ids in their new order; positions are renumbered from 1, ids of other projects match nothing |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 430 | `ProjectsService::reorderStages` | **update** [project_stages](/code/tables/project_stages.md) (`position`) |  |

**Called from:** [api/projects/stage_reorder.php](/code/files/api/projects/stage_reorder.php.md)

### assignTask

*public static* - line 450

Put a task in a project (and optionally a stage), or take it out (null). The task and the project must belong to the same company - a project is one company's work, and a task cannot carry another company's plan.

```php
function assignTask(
    PDO $conn,
    ActorContext $ctx,
    int $taskId,
    ?int $projectId,
    ?int $stageId = null
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `?int` |  | the project to put the task in; null or 0 takes it out of its project |
| `$stageId` | `?int` | `null` | a stage of that project to put it in; null or 0 for none |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 452 | `ProjectsService::assignTask` | read [tasks](/code/tables/tasks.md) |  |
| 468 | `ProjectsService::assignTask` | **update** [tasks](/code/tables/tasks.md) (`project_id`, `project_stage_id`, `updated_datetime`) |  |
| 483 | `ProjectsService::assignTask` | **update** [tasks](/code/tables/tasks.md) (`project_id`, `project_stage_id`, `updated_datetime`) |  |

**Called from:** [api/projects/task_assign.php](/code/files/api/projects/task_assign.php.md)

### createTaskInProject

*public static* - line 499

Create a task straight into a project (and optionally a stage).

🔑 The task is made by TasksService - the ONE create path - so the assigned
email, the bell, workflows and everything else that happens for a new task
happens here too. Only then is it filed under the project, and it takes the
project's company: a project is one company's work.

```php
function createTaskInProject(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    ?int $stageId,
    array $in
): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$stageId` | `?int` |  | a stage of the project to put the task in; null or 0 for none |
| `$in` | `array` |  | the task fields as TasksService::saveTask() takes them; id, ticket_id, parent_task_id and tenant_id are ignored |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 525 | `ProjectsService::createTaskInProject` | **update** [tasks](/code/tables/tasks.md) (`tenant_id`, `project_id`, `project_stage_id`) |  |

**Called from:** [api/projects/task_create.php](/code/files/api/projects/task_create.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### loadRow

*public static* - line 541

Loads one project; throws not found when it does not exist.

```php
function loadRow(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the project to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 543 | `ProjectsService::loadRow` | read [projects](/code/tables/projects.md) |  |

**Called from:** [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md)

### loadForActor

*public static* - line 555

Load a project the caller may touch - out of scope reads as not found.

```php
function loadForActor(PDO $conn, ActorContext $ctx, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the project to load |

**Called from:** [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md), [api/projects/export.php](/code/files/api/projects/export.php.md), [api/projects/get.php](/code/files/api/projects/get.php.md), [api/projects/reports.php](/code/files/api/projects/reports.php.md), [api/projects/tools.php](/code/files/api/projects/tools.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### loadStage

*public static* - line 570

Loads one project stage; throws not found when it does not exist.

```php
function loadStage(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the stage (project_stages.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 572 | `ProjectsService::loadStage` | read [project_stages](/code/tables/project_stages.md) |  |

### assertCanChange

*public static* - line 587

May this caller change this project? Projects -> Settings -> General decides (its team, or everyone with the module); people who manage Projects always may. A system actor (actorId 0 - demo import, scheduled work) is not a person and is not asked.

```php
function assertCanChange(PDO $conn, ActorContext $ctx, array $project): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row, as loaded by `loadForActor()` |

**Called from:** [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### assertScope

*private static* - line 601

Throws not found with `$notFound` when the row's company is outside the actor's companies.

```php
function assertScope(PDO $conn, ActorContext $ctx, array $row, string $notFound): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | any row with a tenant_id column (a project or a task) |
| `$notFound` | `string` |  | the not-found message to throw, e.g. 'Project not found.' |

### sameTenant

*private static* - line 616

True when two company ids are the same company, treating NULL as the Default company.

```php
function sameTenant(PDO $conn, $a, $b): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$a` |  |  | a tenant_id as stored (null = the Default company) |
| `$b` |  |  | the other tenant_id as stored (null = the Default company) |

### storeTenant

*private static* - line 626

The Default company is stored as NULL, the way every scoped table does.

```php
function storeTenant(PDO $conn, ?int $tenantId): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

### validateField

*private static* - line 644

Validates and cleans one project field according to its type in `fieldMap()`, and returns the value to store.

```php
function validateField(PDO $conn, string $field, $v, array $def)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the project column, a key of `fieldMap()` |
| `$v` |  |  | the value as sent |
| `$def` | `array` |  | that field's rule from `fieldMap()`: type, plus max, values or required |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 683 | `ProjectsService::validateField` | read [analysts](/code/tables/analysts.md) |  |

### date

*private static* - line 696

Validates an optional YYYY-MM-DD date; blank gives null.

```php
function date($v): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the date as typed (YYYY-MM-DD); null or blank gives null, anything else invalid throws |

### assertDates

*private static* - line 711

Throws when the end date is before the start date.

```php
function assertDates(?string $start, ?string $end): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$start` | `?string` |  | the start date (YYYY-MM-DD), or null |
| `$end` | `?string` |  | the end date (YYYY-MM-DD), or null |

### str

*private static* - line 724

Trimmed text cut to `$max` characters, or null when blank.

```php
function str($v, int $max): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the text as sent; null or blank gives null |
| `$max` | `int` |  | the most characters to keep |

### same

*private static* - line 738

True when two values are equal as strings; null only equals null.

```php
function same($a, $b): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` |  |  | the stored value |
| `$b` |  |  | the new value |

### source

*private static* - line 745

The history source for the actor: 'api' or 'app'.

```php
function source(ActorContext $ctx): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |

### syncCalendar

*public static* - line 754

Keep the shared Calendar in step (includes/projects/calendar.php). Call it AFTER the write, outside any transaction; it never throws.

```php
function syncCalendar(PDO $conn): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### afterChange

*public static* - line 769

After a change to one project: fire what its health and tolerances now say (includes/projects/alerts.php). Runs as the person who made the change, so the bell does not tell them about it. Never throws.

```php
function afterChange(PDO $conn, int $projectId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### stageClosed

*public static* - line 786

project.stage_closed - by its gate (`$decision`) or by hand (null).

```php
function stageClosed(
    PDO $conn,
    int $projectId,
    int $stageId,
    ?string $decision,
    ?string $next
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$stageId` | `int` |  | the stage (project_stages.id) that closed |
| `$decision` | `?string` |  | the gate decision that closed it ('go', 'go_with_conditions'), or null when closed by hand |
| `$next` | `?string` |  | the name of the stage the gate started, or null |

**Called from:** [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### raidEscalated

*public static* - line 814

project.raid_escalated - a RAID entry needs somebody above the project manager (3.3.0).

```php
function raidEscalated(PDO $conn, int $projectId, array $r): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$r` | `array` |  | the project_raid row just escalated, including escalation_note |

**Called from:** [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### signoffEvent

*public static* - line 836

project.signoff_requested (3.3.0): somebody is named to sign off an item at a stage gate.

```php
function signoffEvent(PDO $conn, int $projectId, int $itemId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the gate checklist item (project_gate_items.id) that needs signing; no event if nobody is named |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 841 | `ProjectsService::signoffEvent` | read [project_gate_items](/code/tables/project_gate_items.md), read [project_stages](/code/tables/project_stages.md) |  |

**Called from:** [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### decideProposal

*public static* - line 866

Approve or reject a proposal (3.3.0, includes/projects/intake.php). Approving records who and when and - when project_proposal_on_approve is 'active' - starts the project; rejecting cancels it. Either way it is audited and the proposer is told. Compare-and-set, so two approvers decide it once.

```php
function decideProposal(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    string $decision,
    $notes
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$decision` | `string` |  | 'approved' or 'rejected' |
| `$notes` |  |  | the decision notes (cut to 20000 characters); required to reject |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 877 | `ProjectsService::decideProposal` | **update** [projects](/code/tables/projects.md) (`approval_status`, `approval_by_id`, `approval_datetime`, `approval_notes`, `status`, `closed_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### proposalEvent

*public static* - line 899

project.proposal_submitted (to the approvers) / project.proposal_decided (to whoever proposed it, when that was an analyst) - 3.3.0. The payload carries the proposer's email so a workflow can tell somebody who asked on a form and has no bell.

```php
function proposalEvent(PDO $conn, int $projectId, string $event): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$event` | `string` |  | 'project.proposal_submitted' or 'project.proposal_decided' |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 906 | `ProjectsService::proposalEvent` | read [projects](/code/tables/projects.md) |  |

**Called from:** [includes/projects/intake.php](/code/files/includes/projects/intake.php.md)

### changeEvent

*public static* - line 941

project.change_raised / project.change_decided (3.3.0) - a change request was raised, or approved or rejected. includes/projects/control.php.

```php
function changeEvent(PDO $conn, int $projectId, string $event, array $cr): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$event` | `string` |  | 'project.change_raised' or 'project.change_decided' |
| `$cr` | `array` |  | the project_change_requests row |

**Called from:** [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### milestoneReached

*public static* - line 967

project.milestone_reached - somebody marked a milestone done (3.3.0).

```php
function milestoneReached(PDO $conn, int $projectId, array $m): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$m` | `array` |  | the project_milestones row, with done_date set |

**Called from:** [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### eventFor

*private static* - line 989

The project as its events carry it, with the health it shows; null if it is gone.

```php
function eventFor(PDO $conn, int $id): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the project |

### dispatch

*private static* - line 1008

Fires a project workflow event with the project's details.

```php
function dispatch(PDO $conn, string $event, int $id, array $extra = []): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$event` | `string` |  | the workflow event name, e.g. 'project.created' |
| `$id` | `int` |  | the project |
| `$extra` | `array` | `[]` | more payload keys added beside 'project', e.g. ['changed' => 'name,status'] |

### touchProject

*public static* - line 1015

Sets the project's updated time to now, for the other project services.

```php
function touchProject(PDO $conn, int $projectId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### touch

*private static* - line 1021

Sets the project's updated time to now.

```php
function touch(PDO $conn, int $projectId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1023 | `ProjectsService::touch` | **update** [projects](/code/tables/projects.md) (`updated_datetime`) |  |

### audit

*public static* - line 1035

Records one change in a project's history; a failure is logged, never thrown.

```php
function audit(
    PDO $conn,
    int $projectId,
    ?int $analystId,
    string $field,
    $old,
    $new,
    string $source = 'app'
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `?int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | what changed: a project column or an action key such as 'stage_added' |
| `$old` |  |  | the value before (cut to 1000 characters), or null |
| `$new` |  |  | the value after (cut to 1000 characters), or null |
| `$source` | `string` | `'app'` | where it came from: 'app', 'api' or 'system' |

**Writes:** project_audit - the project's history

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1038 | `ProjectsService::audit` | **insert** [project_audit](/code/tables/project_audit.md) (`project_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime`) |  |

**Called from:** [includes/projects/control.php](/code/files/includes/projects/control.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md), [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### auditDisplay

*private static* - line 1054

What the history shows for a value: names, not ids; a line, not a paragraph.

```php
function auditDisplay(PDO $conn, string $field, $v): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the project column the value belongs to |
| `$v` |  |  | the stored value |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1058 | `ProjectsService::auditDisplay` | read [analysts](/code/tables/analysts.md) |  |

