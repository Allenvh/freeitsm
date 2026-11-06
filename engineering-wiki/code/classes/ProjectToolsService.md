# ProjectToolsService

> Class in [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), line 20

## Methods

| Method | What it does |
|---|---|
| [addMember()](#addmember) | Add a member: exactly one of analyst_id / team_id / user_id / supplier_id (+ contact_id). Returns its id. |
| [addContractorMember()](#addcontractormember) | A contractor on the team (3.3.0): a supplier, or one person there (which brings its supplier). Suppliers live in Contracts, so adding one needs it. |
| [contractorMemberSql()](#contractormembersql) | The member columns for a contractor (3.3.0), or NULLs before Database Verification. |
| [updateMember()](#updatemember) | Changes a project member's role, notes and stakeholder map details (power, interest, stance, how they are kept informed), and records the change in the project's history. |
| [stakeReady()](#stakeready) | Has Database Verification added the stakeholder columns (3.3.0)? |
| [stakeColumns()](#stakecolumns) | The stakeholder columns for a member query, or NULL placeholders before Database Verification has added them. |
| [removeMember()](#removemember) | Remove a member. Their RACI letters go with them (FK cascade, and by hand). |
| [saveItem()](#saveitem) | Adds or updates a deliverable or requirement (title, description, acceptance criteria, MoSCoW priority, stage, status) and returns its id. |
| [deleteItem()](#deleteitem) | Deletes a deliverable with its RACI rows; any child items lose their parent. Recorded in the project's history. |
| [moveItem()](#moveitem) | Move an item to a MoSCoW column, in the given order within it (the drag on the board). |
| [setRaci()](#setraci) | Set one cell: R, A, C, I or '' to clear. Returns the row's letters after the change ({member_id: letter}), so the screen can redraw a demoted A. |
| [saveRaid()](#saveraid) | Create or update a RAID entry. Probability is for risks only and impact for risks and issues (both 1-5); a risk's score is probability x impact (1-25), which is what the heat map and the risk tolerance read. |
| [deleteRaid()](#deleteraid) | Deletes a RAID log entry; its follow-up tasks are unlinked but kept. Recorded in the project's history. |
| [raidLogReady()](#raidlogready) | Has Database Verification added the 3.3.0 RAID columns (escalation, decision log)? |
| [escalateRaid()](#escalateraid) | Escalate an open entry (3.3.0): it needs somebody above the project manager - a sponsor, the board - to act. Stamped with who and when and a note saying what is needed; shown on the Overview until it is de-escalated or the entry is closed; fires project.raid_escalated, so the project manager's bell rings (unless they escalated it) and a workflow can email the sponsor. Escalating again replaces the note and fires again. |
| [deescalateRaid()](#deescalateraid) | Clears a RAID entry's escalation; does nothing when it is not escalated. Recorded in the project's history. |
| [addRaidAction()](#addraidaction) | A follow-up action on a RAID entry (3.3.0): a NEW project task, made by `createTaskInProject()` (TasksService - so the assigned email, the bell and task events happen as for any task), then joined to the entry. It is put in no stage - where it sits in the plan is the Plan's job. Returns the task id. |
| [removeRaidAction()](#removeraidaction) | Unlink an action. The task itself stays - it is somebody's work. |
| [raidActionsReady()](#raidactionsready) | True when the project_raid_tasks table exists (after Database Verification). Cached per request. |
| [lessonToKnowledge()](#lessontoknowledge) | A lesson becomes a Knowledge article, in one click (3.2.0). |
| [issueToTicket()](#issuetoticket) | Raise a NEW ticket from an issue (3.2.0) - before, an issue could only be linked to a ticket that already existed. |
| [raidRow()](#raidrow) | One RAID entry of this project, or not found. |
| [linkQuietly()](#linkquietly) | Link the new record on the Connections tab too. The record is already made and remembered on the entry, so a link that cannot be added (the tables not verified yet) must not undo it. |
| [saveTolerances()](#savetolerances) | The project's tolerances - how far it may drift before it is an exception (PRINCE2's "manage by exception", in our own words). time = days past the target finish (or the active stage's end) with work still open; risk = the highest open risk score allowed (1-25). null removes one. |
| [tolerances()](#tolerances) | {time: int\|null, risk: int\|null} |
| [decideGate()](#decidegate) | Record the gate at the end of a stage. Go (or go with conditions) closes the stage and starts the next planned one, so the decision IS the hand-over; Stop records the decision and leaves the plan alone - what to do next is the board's call, not the software's. |
| [raid()](#raid) | The project's RAID log - each entry with its owner, ticket, risk score, escalation, lesson article and follow-up tasks. |
| [saveTarget()](#savetarget) | Add or change an asset target. Needs the project to be changeable AND Assets access: the rule names asset types, statuses and models, and the dialog's live count would otherwise tell somebody without Assets what is in the estate. Returns its id. |
| [deleteTarget()](#deletetarget) | Deletes an asset target from a project and records it; health and tolerances are checked again. |
| [assertAssets()](#assertassets) | Assets access, for anything that reads the estate through a target. |
| [target()](#target) | Loads one asset target of the project; throws not found otherwise. |
| [date()](#date) | Validates an optional YYYY-MM-DD date; blank gives null. |
| [saveMilestone()](#savemilestone) | Add or change a milestone: {id?, name, due_date, stage_id?, notes?, done?, done_date?}. Any field left out keeps its value, so the Timeline can move just the date. done = true stamps today (or done_date) and who; done = false clears both. Reaching one fires project.milestone_reached. Returns its id. |
| [deleteMilestone()](#deletemilestone) | Deletes a milestone from a project, records it, and refreshes the calendar, health and tolerances. |
| [milestone()](#milestone) | Loads one milestone of the project; throws not found otherwise. |
| [setTaskDates()](#settaskdates) | Move a project task's dates from the Timeline: {start_date?, due_date?}. |
| [setTaskEstimate()](#settaskestimate) | Set a project task's estimate from the Plan (3.3.0): {estimate_hours} (empty = not estimated). Through TasksService, like `setTaskDates()` - the task window and the REST API write the same column the same way. |
| [members()](#members) | Members with display names, roles and kinds, in order. |
| [items()](#items) | The project's deliverables and requirements, in order, with their stage names; empty before the table exists. |
| [raci()](#raci) | {item_id: {member_id: letter}} |
| [addTaskDependency()](#addtaskdependency) | `$taskId` waits for `$dependsOnId` to finish (+ lag days). Both in this project; no loops. Returns its id. |
| [removeTaskDependency()](#removetaskdependency) | Removes a dependency between two of the project's tasks and records it in the project's history. |
| [saveGateItem()](#savegateitem) | Add (no id) or change a gate item. Returns its id. |
| [deleteGateItem()](#deletegateitem) | Deletes a stage gate item and records it in the project's history. |
| [tickGateItem()](#tickgateitem) | Tick or untick an item. A check: anybody who may change the project. A sign-off: ONLY its named analyst. A document: choose one of the project's documents (document_id; empty unticks). A change cannot be ticked - it is done when the change is approved. |
| [setGateKind()](#setgatekind) | standard \| golive. Making a gate go-live adds the starter items it does not already have. |
| [gateItem()](#gateitem) | Loads one stage gate item of the project; throws not found otherwise. |
| [saveBenefit()](#savebenefit) | Create (no id) or update a benefit. Returns its id. |
| [deleteBenefit()](#deletebenefit) | Deletes a benefit with its measurements and records it in the project's history. |
| [addBenefitMeasure()](#addbenefitmeasure) | Record a measurement. One on or after (review date - 14 days) is the review: the next one moves on by the benefit's review_months (none when 0). Returns the measurement's id. |
| [deleteBenefitMeasure()](#deletebenefitmeasure) | Deletes one measurement of a benefit; throws not found when it is not that benefit's. |
| [benefit()](#benefit) | Loads one benefit of the project; throws not found otherwise. |
| [takeBaseline()](#takebaseline) | Take a baseline of the plan as it is now, by hand. Returns its id. |
| [saveChangeRequest()](#savechangerequest) | Raise a change request (no id), or edit one still waiting for a decision - by whoever raised it, or anybody who may change the project. Returns its id. |
| [decideChangeRequest()](#decidechangerequest) | Approve or reject a change request. Approving takes a new baseline - the changed plan becomes the agreed one - and, when project_change_apply is "plan", first moves the target finish by the days and adds the cost as a budget line, recording both in `applied`. Rejecting changes nothing. |
| [withdrawChangeRequest()](#withdrawchangerequest) | Withdraw a request still waiting for a decision: whoever raised it, or the project's team. |
| [changeRequest()](#changerequest) | Loads one change request of the project; throws not found otherwise. |
| [changeable()](#changeable) | Loads the project for the actor and checks they may change it; throws otherwise. |
| [member()](#member) | Loads one member row of the project; throws not found otherwise. |
| [item()](#item) | Loads one deliverable of the project; throws not found otherwise. |
| [moscow()](#moscow) | A validated MoSCoW value in lower case, or null for none. |
| [stageOf()](#stageof) | A validated stage id of this project, or null for none. |
| [roleId()](#roleid) | A validated project role id, or null for none. |
| [roleName()](#rolename) | The name of a project role, or null. |
| [memberName()](#membername) | The name of a project member - analyst, team, portal user or contractor - or #id. |
| [mustExist()](#mustexist) | Runs a one-id lookup query and throws a validation error with `$msg` when it finds nothing. |
| [str()](#str) | Trimmed text cut to `$max` characters, or null when blank. |
| [src()](#src) | The history source for the actor: 'api' or 'app'. |
| [saveBudgetLine()](#savebudgetline) | Create (no id) or update a budget line. Returns its id. |
| [budgetLineWhen()](#budgetlinewhen) | The 3.3.0 columns, on their own: before Database Verification adds them the rest of the line still saves. |
| [deleteBudgetLine()](#deletebudgetline) | Deletes a budget line, records it in the project's history, and checks health and tolerances again. |
| [addProjectRate()](#addprojectrate) | The project's own hourly rate from a date (labour mode "rate"), in the project's currency. Each rate is a new row: the old one still prices the time logged before the new one's date. |
| [deleteProjectRate()](#deleteprojectrate) | Remove one of the project's own rates (entered by mistake). |
| [setCurrency()](#setcurrency) | Change the project's currency - a RELABEL: amounts are not converted, and the page says so before anyone presses Save. Only when Projects -> Settings -> Budget lets projects choose. |
| [announce()](#announce) | Announce planned disruption from a project - "Move day: phones down Saturday". How depends on Projects -> Settings -> General: planned planned maintenance: upcoming until its start, then an incident now the same, starting now - an incident straight away off refused (the button is not shown) Needs Service Status as well as being allowed to change the project. Service Status is install-wide, like contracts: no company check on the services. Returns the planned maintenance id. |
| [withdrawAnnouncement()](#withdrawannouncement) | Withdraw an announcement that has not started. It must belong to this project. |
| [announcements()](#announcements) | What the Connections tab needs: the mode, this project's announcements, and the services and impact levels for the dialog. null when the analyst cannot open Service Status - then the panel is not drawn at all. |

### addMember

*public static* - line 36

Add a member: exactly one of analyst_id / team_id / user_id / supplier_id (+ contact_id). Returns its id.

```php
function addMember(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | exactly one of analyst_id, team_id, user_id (a person in the project's company) or supplier_id (with optional contact_id); plus role_id and notes (up to 255) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `ProjectToolsService::addMember` | read [analysts](/code/tables/analysts.md) |  |
| 45 | `ProjectToolsService::addMember` | read [teams](/code/tables/teams.md) |  |
| 49 | `ProjectToolsService::addMember` | read [users](/code/tables/users.md) |  |
| 61 | `ProjectToolsService::addMember` | read [project_members](/code/tables/project_members.md) |  |
| 65 | `ProjectToolsService::addMember` | read [project_members](/code/tables/project_members.md) |  |
| 66 | `ProjectToolsService::addMember` | **insert** [project_members](/code/tables/project_members.md) (`project_id`, `$col`, `role_id`, `notes`, `position`, `created_by_analyst_id`, `created_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### addContractorMember

*private static* - line 80

A contractor on the team (3.3.0): a supplier, or one person there (which brings its supplier). Suppliers live in Contracts, so adding one needs it.

```php
function addContractorMember(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | supplier_id and/or contact_id (a contact brings its supplier), role_id, notes |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 84 | `ProjectToolsService::addContractorMember` | read [project_members](/code/tables/project_members.md) |  |
| 92 | `ProjectToolsService::addContractorMember` | read [project_members](/code/tables/project_members.md) |  |
| 96 | `ProjectToolsService::addContractorMember` | read [project_members](/code/tables/project_members.md) |  |
| 97 | `ProjectToolsService::addContractorMember` | **insert** [project_members](/code/tables/project_members.md) (`project_id`, `supplier_id`, `contact_id`, `role_id`, `notes`, `position`, `created_by_analyst_id`, `created_datetime`) |  |

### contractorMemberSql

*private static* - line 106

The member columns for a contractor (3.3.0), or NULLs before Database Verification.

```php
function contractorMemberSql(PDO $conn): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 110 | `ProjectToolsService::contractorMemberSql` | read [project_members](/code/tables/project_members.md) |  |
| 110 | `ProjectToolsService::contractorMemberSql` | read [suppliers](/code/tables/suppliers.md) |  |
| 117 | `ProjectToolsService::contractorMemberSql` | read [suppliers](/code/tables/suppliers.md), read [contacts](/code/tables/contacts.md) |  |

### updateMember

*public static* - line 129

Changes a project member's role, notes and stakeholder map details (power, interest, stance, how they are kept informed), and records the change in the project's history.

```php
function updateMember(PDO $conn, ActorContext $ctx, int $projectId, int $memberId, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$memberId` | `int` |  | the member (project_members.id) to change |
| `$in` | `array` |  | only the fields to change: role_id, notes, power and interest (1-5, empty = not placed), stance (champion, supporter, neutral, sceptic, blocker), keep_informed |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 154 | `ProjectToolsService::updateMember` | **update** [project_members](/code/tables/project_members.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### stakeReady

*public static* - line 164

Has Database Verification added the stakeholder columns (3.3.0)?

```php
function stakeReady(PDO $conn): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 168 | `ProjectToolsService::stakeReady` | read [project_members](/code/tables/project_members.md) |  |

**Called from:** [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)

### stakeColumns

*private static* - line 178

The stakeholder columns for a member query, or NULL placeholders before Database Verification has added them.

```php
function stakeColumns(PDO $conn): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

### removeMember

*public static* - line 188

Remove a member. Their RACI letters go with them (FK cascade, and by hand).

```php
function removeMember(PDO $conn, ActorContext $ctx, int $projectId, int $memberId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$memberId` | `int` |  | the member (project_members.id) to remove |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 193 | `ProjectToolsService::removeMember` | **delete** [project_raci](/code/tables/project_raci.md) |  |
| 194 | `ProjectToolsService::removeMember` | **delete** [project_members](/code/tables/project_members.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### saveItem

*public static* - line 212

Adds or updates a deliverable or requirement (title, description, acceptance criteria, MoSCoW priority, stage, status) and returns its id.

A new item goes to the end of the list; adding one, or changing its MoSCoW priority,
is recorded in the project's history.

```php
function saveItem(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (an existing item to change; absent for a new one), title, description, acceptance_criteria, moscow, stage_id, status (proposed, agreed, in_progress, accepted, dropped) - fields left out keep their value |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 227 | `ProjectToolsService::saveItem` | **update** [project_items](/code/tables/project_items.md) (`title`, `description`, `acceptance_criteria`, `moscow`, `stage_id`, `status`, `updated_datetime`) |  |
| 234 | `ProjectToolsService::saveItem` | read [project_items](/code/tables/project_items.md) |  |
| 235 | `ProjectToolsService::saveItem` | **insert** [project_items](/code/tables/project_items.md) (`project_id`, `title`, `description`, `acceptance_criteria`, `moscow`, `stage_id`, `status`, `position`, `created_datetime`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### deleteItem

*public static* - line 249

Deletes a deliverable with its RACI rows; any child items lose their parent. Recorded in the project's history.

```php
function deleteItem(PDO $conn, ActorContext $ctx, int $projectId, int $itemId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the deliverable or requirement (project_items.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 253 | `ProjectToolsService::deleteItem` | **delete** [project_raci](/code/tables/project_raci.md) |  |
| 254 | `ProjectToolsService::deleteItem` | **update** [project_items](/code/tables/project_items.md) (`parent_id`) |  |
| 255 | `ProjectToolsService::deleteItem` | **delete** [project_items](/code/tables/project_items.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md)

### moveItem

*public static* - line 266

Move an item to a MoSCoW column, in the given order within it (the drag on the board).

```php
function moveItem(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    int $itemId,
    $moscow,
    array $orderedIds
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the item (project_items.id) that was dragged |
| `$moscow` |  |  | the column it was dropped in: must, should, could, wont, or null/'' for unprioritised |
| `$orderedIds` | `array` |  | the item ids in that column in their new order; positions are renumbered from 1 |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 271 | `ProjectToolsService::moveItem` | **update** [project_items](/code/tables/project_items.md) (`moscow`, `updated_datetime`) |  |
| 272 | `ProjectToolsService::moveItem` | **update** [project_items](/code/tables/project_items.md) (`position`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### setRaci

*public static* - line 292

Set one cell: R, A, C, I or '' to clear. Returns the row's letters after the change ({member_id: letter}), so the screen can redraw a demoted A.

```php
function setRaci(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    int $itemId,
    int $memberId,
    string $letter
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the deliverable (project_items.id) - the row |
| `$memberId` | `int` |  | the member (project_members.id) - the column |
| `$letter` | `string` |  | R, A, C or I (any case), or '' to clear the cell |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 300 | `ProjectToolsService::setRaci` | **delete** [project_raci](/code/tables/project_raci.md) |  |
| 304 | `ProjectToolsService::setRaci` | **update** [project_raci](/code/tables/project_raci.md) (`letter`) |  |
| 306 | `ProjectToolsService::setRaci` | **insert** [project_raci](/code/tables/project_raci.md) (`project_id`, `item_id`, `member_id`, `letter`) |  |
| 310 | `ProjectToolsService::setRaci` | read [project_raci](/code/tables/project_raci.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### saveRaid

*public static* - line 343

Create or update a RAID entry. Probability is for risks only and impact for risks and issues (both 1-5); a risk's score is probability x impact (1-25), which is what the heat map and the risk tolerance read.

Decisions (3.3.0) also carry the DECISION LOG: decided_by (a name - the
person deciding is often a sponsor with no analyst account), decided_date
and rationale. Closing a decision means it was made, so a decision closed
with no date is stamped today. A decision still open past its due date,
like a dependency not arrived by its due date, turns health amber
(projectAutoHealth).

```php
function saveRaid(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (an entry to change; absent for new), type (risk, assumption, issue, dependency, decision, lesson), title, description, probability and impact (1-5), response, response_plan, owner_analyst_id, status (open/closed), due_date, ticket_id, and for decisions decided_by, decided_date, rationale |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 349 | `ProjectToolsService::saveRaid` | read [project_raid](/code/tables/project_raid.md) |  |
| 367 | `ProjectToolsService::saveRaid` | read [analysts](/code/tables/analysts.md) |  |
| 396 | `ProjectToolsService::saveRaid` | **update** [project_raid](/code/tables/project_raid.md) (`type`, `title`, `description`, `probability`, `impact`, `response`, `response_plan`, `owner_analyst_id`, `status`, `due_date`, `ticket_id`, `updated_datetime`) |  |
| 407 | `ProjectToolsService::saveRaid` | **insert** [project_raid](/code/tables/project_raid.md) (`type`, `title`, `description`, `probability`, `impact`, `response`, `response_plan`, `owner_analyst_id`, `status`, `due_date`, `ticket_id`, `project_id`) |  |
| 412 | `ProjectToolsService::saveRaid` | **update** [project_raid](/code/tables/project_raid.md) (`decided_by`, `decided_date`, `rationale`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### deleteRaid

*public static* - line 425

Deletes a RAID log entry; its follow-up tasks are unlinked but kept. Recorded in the project's history.

```php
function deleteRaid(PDO $conn, ActorContext $ctx, int $projectId, int $raidId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$raidId` | `int` |  | the RAID entry (project_raid.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 428 | `ProjectToolsService::deleteRaid` | read [project_raid](/code/tables/project_raid.md) |  |
| 433 | `ProjectToolsService::deleteRaid` | **delete** [project_raid_tasks](/code/tables/project_raid_tasks.md) |  |
| 434 | `ProjectToolsService::deleteRaid` | **delete** [project_raid](/code/tables/project_raid.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md)

### raidLogReady

*public static* - line 440

Has Database Verification added the 3.3.0 RAID columns (escalation, decision log)?

```php
function raidLogReady(PDO $conn): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 444 | `ProjectToolsService::raidLogReady` | read [project_raid](/code/tables/project_raid.md) |  |

### escalateRaid

*public static* - line 461

Escalate an open entry (3.3.0): it needs somebody above the project manager - a sponsor, the board - to act. Stamped with who and when and a note saying what is needed; shown on the Overview until it is de-escalated or the entry is closed; fires project.raid_escalated, so the project manager's bell rings (unless they escalated it) and a workflow can email the sponsor. Escalating again replaces the note and fires again.

```php
function escalateRaid(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    int $raidId,
    ?string $note
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$raidId` | `int` |  | the open RAID entry (project_raid.id) to escalate |
| `$note` | `?string` |  | what is needed and from whom (required, cut to 500 characters) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 469 | `ProjectToolsService::escalateRaid` | **update** [project_raid](/code/tables/project_raid.md) (`escalated_datetime`, `escalated_by_id`, `escalation_note`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### deescalateRaid

*public static* - line 482

Clears a RAID entry's escalation; does nothing when it is not escalated. Recorded in the project's history.

```php
function deescalateRaid(PDO $conn, ActorContext $ctx, int $projectId, int $raidId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$raidId` | `int` |  | the RAID entry (project_raid.id) to de-escalate |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 488 | `ProjectToolsService::deescalateRaid` | **update** [project_raid](/code/tables/project_raid.md) (`escalated_datetime`, `escalated_by_id`, `escalation_note`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### addRaidAction

*public static* - line 502

A follow-up action on a RAID entry (3.3.0): a NEW project task, made by `createTaskInProject()` (TasksService - so the assigned email, the bell and task events happen as for any task), then joined to the entry. It is put in no stage - where it sits in the plan is the Plan's job. Returns the task id.

```php
function addRaidAction(PDO $conn, ActorContext $ctx, int $projectId, int $raidId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$raidId` | `int` |  | the RAID entry (project_raid.id) the action follows up |
| `$in` | `array` |  | title (required), assigned_analyst_id, due_date (YYYY-MM-DD) for the new task |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 515 | `ProjectToolsService::addRaidAction` | **insert** [project_raid_tasks](/code/tables/project_raid_tasks.md) (`raid_id`, `task_id`, `created_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### removeRaidAction

*public static* - line 525

Unlink an action. The task itself stays - it is somebody's work.

```php
function removeRaidAction(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    int $raidId,
    int $taskId
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$raidId` | `int` |  | the RAID entry (project_raid.id) to unlink the task from |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 530 | `ProjectToolsService::removeRaidAction` | **delete** [project_raid_tasks](/code/tables/project_raid_tasks.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### raidActionsReady

*public static* - line 534

True when the project_raid_tasks table exists (after Database Verification). Cached per request.

```php
function raidActionsReady(PDO $conn): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 538 | `ProjectToolsService::raidActionsReady` | read [project_raid_tasks](/code/tables/project_raid_tasks.md) |  |

### lessonToKnowledge

*public static* - line 557

A lesson becomes a Knowledge article, in one click (3.2.0).

Saved as a DRAFT through KnowledgeService, like the Knowledge assistant's
write-ups: somebody reads it before anyone else can, and it gets the same
validation, search entry and history as any article. It belongs to the
project's company (an MSP's lesson is that client's until somebody shares
it), is linked on the project's Connections tab, and remembered on the
lesson so it is made once. Needs Knowledge as well as being allowed to
change the project. Returns ['id', 'url'].

```php
function lessonToKnowledge(PDO $conn, ActorContext $ctx, int $projectId, int $raidId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$raidId` | `int` |  | the lesson (a project_raid row of type 'lesson') to turn into an article |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 586 | `ProjectToolsService::lessonToKnowledge` | **update** [project_raid](/code/tables/project_raid.md) (`knowledge_article_id`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### issueToTicket

*public static* - line 607

Raise a NEW ticket from an issue (3.2.0) - before, an issue could only be linked to a ticket that already existed.

Through TicketsService::createTicket(), so it numbers, routes, notifies and
fires ticket.created like any ticket. It is filed in the project's company,
raised by (requester) the analyst pressing the button - it is the IT team's
own work - and assigned to the issue's owner, else to them, as a ticket
made by hand is. The issue then points at it, and the project is linked to
it, so it shows on the ticket and on the Connections tab. Needs Tickets.
Returns ['id', 'number', 'url'].

```php
function issueToTicket(PDO $conn, ActorContext $ctx, int $projectId, int $raidId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$raidId` | `int` |  | the issue (a project_raid row of type 'issue') to raise a ticket from |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 616 | `ProjectToolsService::issueToTicket` | read [analysts](/code/tables/analysts.md) |  |
| 641 | `ProjectToolsService::issueToTicket` | **update** [project_raid](/code/tables/project_raid.md) (`ticket_id`, `updated_datetime`) |  |
| 643 | `ProjectToolsService::issueToTicket` | read [tickets](/code/tables/tickets.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### raidRow

*private static* - line 656

One RAID entry of this project, or not found.

```php
function raidRow(PDO $conn, int $projectId, int $raidId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$raidId` | `int` |  | the RAID entry (project_raid.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 658 | `ProjectToolsService::raidRow` | read [project_raid](/code/tables/project_raid.md) |  |

### linkQuietly

*private static* - line 673

Link the new record on the Connections tab too. The record is already made and remembered on the entry, so a link that cannot be added (the tables not verified yet) must not undo it.

```php
function linkQuietly(PDO $conn, ActorContext $ctx, int $projectId, string $kind, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the kind of record to link, as `projectLinkAdd()` takes it: 'ticket' or 'article' here |
| `$id` | `int` |  | the id of that record |

### saveTolerances

*public static* - line 698

The project's tolerances - how far it may drift before it is an exception (PRINCE2's "manage by exception", in our own words). time = days past the target finish (or the active stage's end) with work still open; risk = the highest open risk score allowed (1-25). null removes one.

```php
function saveTolerances(PDO $conn, ActorContext $ctx, int $projectId, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | any of time (days late, 0-365), risk (highest open risk score, 1-25), cost (overspend percent, 0-500); null or '' removes that tolerance, a key left out is untouched |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 706 | `ProjectToolsService::saveTolerances` | **delete** [project_tolerances](/code/tables/project_tolerances.md) |  |
| 712 | `ProjectToolsService::saveTolerances` | read [project_tolerances](/code/tables/project_tolerances.md) |  |
| 715 | `ProjectToolsService::saveTolerances` | **update** [project_tolerances](/code/tables/project_tolerances.md) (`value`) |  |
| 717 | `ProjectToolsService::saveTolerances` | **insert** [project_tolerances](/code/tables/project_tolerances.md) (`project_id`, `stage_id`, `dimension`, `value`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### tolerances

*public static* - line 726

{time: int|null, risk: int|null}

```php
function tolerances(PDO $conn, int $projectId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 730 | `ProjectToolsService::tolerances` | read [project_tolerances](/code/tables/project_tolerances.md) |  |

**Called from:** [api/projects/get.php](/code/files/api/projects/get.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md)

### decideGate

*public static* - line 748

Record the gate at the end of a stage. Go (or go with conditions) closes the stage and starts the next planned one, so the decision IS the hand-over; Stop records the decision and leaves the plan alone - what to do next is the board's call, not the software's.

```php
function decideGate(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    int $stageId,
    string $decision,
    ?string $notes
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$stageId` | `int` |  | the stage (project_stages.id) whose end-of-stage gate is decided; must have started |
| `$decision` | `string` |  | 'go', 'go_with_conditions' or 'stop' |
| `$notes` | `?string` |  | the board's notes; required for go with conditions (cut to 20000 characters) |

**Returns:** array{closed:bool, next:?string}

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 752 | `ProjectToolsService::decideGate` | read [project_stages](/code/tables/project_stages.md) |  |
| 775 | `ProjectToolsService::decideGate` | **update** [project_stages](/code/tables/project_stages.md) (`gate_decision`, `gate_notes`, `gate_decided_by`, `gate_decided_datetime`, `updated_datetime`) |  |
| 782 | `ProjectToolsService::decideGate` | **update** [project_stages](/code/tables/project_stages.md) (`status`) |  |
| 784 | `ProjectToolsService::decideGate` | read [project_stages](/code/tables/project_stages.md) |  |
| 787 | `ProjectToolsService::decideGate` | **update** [project_stages](/code/tables/project_stages.md) (`status`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md)

### raid

*public static* - line 816

The project's RAID log - each entry with its owner, ticket, risk score, escalation, lesson article and follow-up tasks.

Highest risk score first, closed entries last. Empty when the tables are not there.

```php
function raid(PDO $conn, int $projectId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 822 | `ProjectToolsService::raid` | read [project_raid](/code/tables/project_raid.md), read [analysts](/code/tables/analysts.md) |  |
| 825 | `ProjectToolsService::raid` | read [analysts](/code/tables/analysts.md) |  |
| 825 | `ProjectToolsService::raid` | read [tickets](/code/tables/tickets.md) |  |
| 838 | `ProjectToolsService::raid` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 844 | `ProjectToolsService::raid` | read [project_raid_tasks](/code/tables/project_raid_tasks.md), read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [analysts](/code/tables/analysts.md) |  |

**Called from:** [api/projects/export.php](/code/files/api/projects/export.php.md), [api/projects/get.php](/code/files/api/projects/get.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md)

### saveTarget

*public static* - line 881

Add or change an asset target. Needs the project to be changeable AND Assets access: the rule names asset types, statuses and models, and the dialog's live count would otherwise tell somebody without Assets what is in the estate. Returns its id.

```php
function saveTarget(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the rule as `projectTargetNormalise()` reads it (name, scope, scope_type_id, scope_field, scope_value, done_field, done_op, done_value, target_date); id to change an existing target |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 895 | `ProjectToolsService::saveTarget` | **update** [project_asset_targets](/code/tables/project_asset_targets.md) (`name`, `scope`, `scope_type_id`, `scope_field`, `scope_value`, `done_field`, `done_op`, `done_value`, `target_date`, `updated_datetime`) |  |
| 899 | `ProjectToolsService::saveTarget` | **delete** [project_asset_target_snapshots](/code/tables/project_asset_target_snapshots.md) |  |
| 901 | `ProjectToolsService::saveTarget` | read [project_asset_targets](/code/tables/project_asset_targets.md) |  |
| 903 | `ProjectToolsService::saveTarget` | **insert** [project_asset_targets](/code/tables/project_asset_targets.md) (`project_id`, `name`, `scope`, `scope_type_id`, `scope_field`, `scope_value`, `done_field`, `done_op`, `done_value`, `target_date`, `position`, `created_by_analyst_id`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### deleteTarget

*public static* - line 919

Deletes an asset target from a project and records it; health and tolerances are checked again.

```php
function deleteTarget(PDO $conn, ActorContext $ctx, int $projectId, int $targetId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$targetId` | `int` |  | the asset target (project_asset_targets.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 923 | `ProjectToolsService::deleteTarget` | **delete** [project_asset_targets](/code/tables/project_asset_targets.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### assertAssets

*public static* - line 930

Assets access, for anything that reads the estate through a target.

```php
function assertAssets(PDO $conn, ActorContext $ctx): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### target

*public static* - line 942

Loads one asset target of the project; throws not found otherwise.

```php
function target(PDO $conn, int $projectId, int $targetId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$targetId` | `int` |  | the asset target (project_asset_targets.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 944 | `ProjectToolsService::target` | read [project_asset_targets](/code/tables/project_asset_targets.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### date

*private static* - line 957

Validates an optional YYYY-MM-DD date; blank gives null.

```php
function date($v): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the date as typed (YYYY-MM-DD); null or blank gives null, anything else invalid throws |

### saveMilestone

*public static* - line 980

Add or change a milestone: {id?, name, due_date, stage_id?, notes?, done?, done_date?}. Any field left out keeps its value, so the Timeline can move just the date. done = true stamps today (or done_date) and who; done = false clears both. Reaching one fires project.milestone_reached. Returns its id.

```php
function saveMilestone(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (to change one), name (up to 150), due_date, stage_id, notes, done (true/false), done_date - fields left out keep their value |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1009 | `ProjectToolsService::saveMilestone` | **update** [project_milestones](/code/tables/project_milestones.md) (`name`, `due_date`, `stage_id`, `notes`, `done_date`, `done_by_analyst_id`, `updated_datetime`) |  |
| 1015 | `ProjectToolsService::saveMilestone` | read [project_milestones](/code/tables/project_milestones.md) |  |
| 1017 | `ProjectToolsService::saveMilestone` | **insert** [project_milestones](/code/tables/project_milestones.md) (`project_id`, `stage_id`, `name`, `due_date`, `done_date`, `done_by_analyst_id`, `notes`, `position`, `created_by_analyst_id`, `created_datetime`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### deleteMilestone

*public static* - line 1037

Deletes a milestone from a project, records it, and refreshes the calendar, health and tolerances.

```php
function deleteMilestone(PDO $conn, ActorContext $ctx, int $projectId, int $milestoneId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$milestoneId` | `int` |  | the milestone (project_milestones.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1042 | `ProjectToolsService::deleteMilestone` | **delete** [project_milestones](/code/tables/project_milestones.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### milestone

*private static* - line 1054

Loads one milestone of the project; throws not found otherwise.

```php
function milestone(PDO $conn, int $projectId, int $milestoneId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$milestoneId` | `int` |  | the milestone (project_milestones.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1057 | `ProjectToolsService::milestone` | read [project_milestones](/code/tables/project_milestones.md) |  |

### setTaskDates

*public static* - line 1075

Move a project task's dates from the Timeline: {start_date?, due_date?}.

🔑 Through TasksService - the one task write path - so the task's own
history, events and calendar sync happen as if it were moved on the Tasks
board. The Projects side only checks that the task IS in this project and
that the caller may change the project; Tasks access is not required, the
same as adding a task from the Plan.

```php
function setTaskDates(PDO $conn, ActorContext $ctx, int $projectId, int $taskId, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | start_date and/or due_date (YYYY-MM-DD, '' clears); keys left out are untouched |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1078 | `ProjectToolsService::setTaskDates` | read [tasks](/code/tables/tasks.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### setTaskEstimate

*public static* - line 1099

Set a project task's estimate from the Plan (3.3.0): {estimate_hours} (empty = not estimated). Through TasksService, like `setTaskDates()` - the task window and the REST API write the same column the same way.

```php
function setTaskEstimate(PDO $conn, ActorContext $ctx, int $projectId, int $taskId, $hours): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |
| `$hours` |  |  | the estimate in hours as typed; empty means not estimated (TasksService validates it) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1102 | `ProjectToolsService::setTaskEstimate` | read [tasks](/code/tables/tasks.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### members

*public static* - line 1115

Members with display names, roles and kinds, in order.

```php
function members(PDO $conn, int $projectId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1124 | `ProjectToolsService::members` | read [project_members](/code/tables/project_members.md), read [project_roles](/code/tables/project_roles.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md), read [users](/code/tables/users.md) |  |

**Called from:** [api/projects/get.php](/code/files/api/projects/get.php.md), [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md)

### items

*public static* - line 1142

The project's deliverables and requirements, in order, with their stage names; empty before the table exists.

```php
function items(PDO $conn, int $projectId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1145 | `ProjectToolsService::items` | read [project_items](/code/tables/project_items.md), read [project_stages](/code/tables/project_stages.md) |  |

**Called from:** [api/projects/get.php](/code/files/api/projects/get.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md)

### raci

*public static* - line 1155

{item_id: {member_id: letter}}

```php
function raci(PDO $conn, int $projectId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1159 | `ProjectToolsService::raci` | read [project_raci](/code/tables/project_raci.md) |  |

**Called from:** [api/projects/get.php](/code/files/api/projects/get.php.md)

### addTaskDependency

*public static* - line 1176

`$taskId` waits for `$dependsOnId` to finish (+ lag days). Both in this project; no loops. Returns its id.

```php
function addTaskDependency(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    int $taskId,
    int $dependsOnId,
    $lag
): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |
| `$dependsOnId` | `int` |  | the task that must finish first; must be in the same project and not make a loop |
| `$lag` |  |  | days to wait after it finishes, -365..365; null or '' means 0 |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1183 | `ProjectToolsService::addTaskDependency` | read [tasks](/code/tables/tasks.md) |  |
| 1192 | `ProjectToolsService::addTaskDependency` | **insert** [task_dependencies](/code/tables/task_dependencies.md) (`task_id`, `depends_on_id`, `lag_days`, `created_by_id`, `created_datetime`) |  |
| 1195 | `ProjectToolsService::addTaskDependency` | read [task_dependencies](/code/tables/task_dependencies.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### removeTaskDependency

*public static* - line 1207

Removes a dependency between two of the project's tasks and records it in the project's history.

```php
function removeTaskDependency(PDO $conn, ActorContext $ctx, int $projectId, int $depId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$depId` | `int` |  | the dependency (task_dependencies.id) to remove; must belong to this project's tasks |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1213 | `ProjectToolsService::removeTaskDependency` | **delete** [task_dependencies](/code/tables/task_dependencies.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### saveGateItem

*public static* - line 1228

Add (no id) or change a gate item. Returns its id.

```php
function saveGateItem(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (to change one), stage_id (new items only), kind (new items only: check, signoff, document, change), title (up to 200), analyst_id (sign-off), change_id (change), notes (up to 500) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1243 | `ProjectToolsService::saveGateItem` | read [analysts](/code/tables/analysts.md) |  |
| 1252 | `ProjectToolsService::saveGateItem` | **update** [project_gate_items](/code/tables/project_gate_items.md) (`title`, `analyst_id`, `change_id`, `notes`) |  |
| 1256 | `ProjectToolsService::saveGateItem` | read [project_gate_items](/code/tables/project_gate_items.md) |  |
| 1257 | `ProjectToolsService::saveGateItem` | **insert** [project_gate_items](/code/tables/project_gate_items.md) (`project_id`, `stage_id`, `kind`, `title`, `analyst_id`, `change_id`, `notes`, `position`, `created_by_id`, `created_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### deleteGateItem

*public static* - line 1273

Deletes a stage gate item and records it in the project's history.

```php
function deleteGateItem(PDO $conn, ActorContext $ctx, int $projectId, int $itemId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the gate checklist item (project_gate_items.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1277 | `ProjectToolsService::deleteGateItem` | **delete** [project_gate_items](/code/tables/project_gate_items.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### tickGateItem

*public static* - line 1291

Tick or untick an item. A check: anybody who may change the project. A sign-off: ONLY its named analyst. A document: choose one of the project's documents (document_id; empty unticks). A change cannot be ticked - it is done when the change is approved.

```php
function tickGateItem(PDO $conn, ActorContext $ctx, int $projectId, int $itemId, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the gate checklist item (project_gate_items.id) to tick or untick |
| `$in` | `array` |  | done (true ticks), notes (sign-off only), document_id (document items; empty unticks) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1303 | `ProjectToolsService::tickGateItem` | **update** [project_gate_items](/code/tables/project_gate_items.md) (`done_by_id`, `done_datetime`) |  |
| 1312 | `ProjectToolsService::tickGateItem` | **update** [project_gate_items](/code/tables/project_gate_items.md) (`document_id`, `done_by_id`, `done_datetime`) |  |
| 1318 | `ProjectToolsService::tickGateItem` | **update** [project_gate_items](/code/tables/project_gate_items.md) (`done_by_id`, `done_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### setGateKind

*public static* - line 1331

standard | golive. Making a gate go-live adds the starter items it does not already have.

```php
function setGateKind(PDO $conn, ActorContext $ctx, int $projectId, int $stageId, string $kind): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$stageId` | `int` |  | the stage (project_stages.id) whose gate to change |
| `$kind` | `string` |  | 'standard' or 'golive' |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1338 | `ProjectToolsService::setGateKind` | **update** [project_stages](/code/tables/project_stages.md) (`gate_kind`, `updated_datetime`) |  |
| 1341 | `ProjectToolsService::setGateKind` | read [project_gate_items](/code/tables/project_gate_items.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### gateItem

*private static* - line 1362

Loads one stage gate item of the project; throws not found otherwise.

```php
function gateItem(PDO $conn, int $projectId, int $itemId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the gate checklist item (project_gate_items.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1365 | `ProjectToolsService::gateItem` | read [project_gate_items](/code/tables/project_gate_items.md) |  |

### saveBenefit

*public static* - line 1385

Create (no id) or update a benefit. Returns its id.

```php
function saveBenefit(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (to change one), title (required), measure, unit, direction (up/down), baseline_value, target_value, target_date, owner_analyst_id, review_date, review_months (0-24; empty uses the setting), status, notes |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1405 | `ProjectToolsService::saveBenefit` | read [analysts](/code/tables/analysts.md) |  |
| 1414 | `ProjectToolsService::saveBenefit` | **update** [project_benefits](/code/tables/project_benefits.md) (`title`, `measure`, `unit`, `direction`, `baseline_value`, `target_value`, `target_date`, `owner_analyst_id`, `review_date`, `review_months`, `status`, `notes`) |  |
| 1421 | `ProjectToolsService::saveBenefit` | read [project_benefits](/code/tables/project_benefits.md) |  |
| 1422 | `ProjectToolsService::saveBenefit` | **insert** [project_benefits](/code/tables/project_benefits.md) (`title`, `measure`, `unit`, `direction`, `baseline_value`, `target_value`, `target_date`, `owner_analyst_id`, `review_date`, `review_months`, `status`, `notes`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### deleteBenefit

*public static* - line 1438

Deletes a benefit with its measurements and records it in the project's history.

```php
function deleteBenefit(PDO $conn, ActorContext $ctx, int $projectId, int $benefitId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$benefitId` | `int` |  | the benefit (project_benefits.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1442 | `ProjectToolsService::deleteBenefit` | **delete** [project_benefit_measures](/code/tables/project_benefit_measures.md) |  |
| 1443 | `ProjectToolsService::deleteBenefit` | **delete** [project_benefits](/code/tables/project_benefits.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### addBenefitMeasure

*public static* - line 1456

Record a measurement. One on or after (review date - 14 days) is the review: the next one moves on by the benefit's review_months (none when 0). Returns the measurement's id.

```php
function addBenefitMeasure(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    int $benefitId,
    array $in
): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$benefitId` | `int` |  | the benefit (project_benefits.id) measured |
| `$in` | `array` |  | value (a number, up to 2 decimals), measured_date (default today, not in the future), note |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1465 | `ProjectToolsService::addBenefitMeasure` | **insert** [project_benefit_measures](/code/tables/project_benefit_measures.md) (`benefit_id`, `value`, `measured_date`, `note`, `recorded_by_id`, `created_datetime`) |  |
| 1471 | `ProjectToolsService::addBenefitMeasure` | **update** [project_benefits](/code/tables/project_benefits.md) (`review_date`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### deleteBenefitMeasure

*public static* - line 1484

Deletes one measurement of a benefit; throws not found when it is not that benefit's.

```php
function deleteBenefitMeasure(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    int $benefitId,
    int $measureId
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$benefitId` | `int` |  | the benefit (project_benefits.id) the measurement belongs to |
| `$measureId` | `int` |  | the measurement (project_benefit_measures.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1488 | `ProjectToolsService::deleteBenefitMeasure` | **delete** [project_benefit_measures](/code/tables/project_benefit_measures.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### benefit

*private static* - line 1499

Loads one benefit of the project; throws not found otherwise.

```php
function benefit(PDO $conn, int $projectId, int $benefitId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$benefitId` | `int` |  | the benefit (project_benefits.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1502 | `ProjectToolsService::benefit` | read [project_benefits](/code/tables/project_benefits.md) |  |

### takeBaseline

*public static* - line 1523

Take a baseline of the plan as it is now, by hand. Returns its id.

```php
function takeBaseline(PDO $conn, ActorContext $ctx, int $projectId, $label): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$label` |  |  | an optional name for the baseline (cut to 150 characters); null or blank for none |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1530 | `ProjectToolsService::takeBaseline` | read [project_baselines](/code/tables/project_baselines.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### saveChangeRequest

*public static* - line 1543

Raise a change request (no id), or edit one still waiting for a decision - by whoever raised it, or anybody who may change the project. Returns its id.

```php
function saveChangeRequest(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (to edit one still proposed), title (required), description, reason, impact_days (-3650..3650), impact_cost (an amount), impact_scope |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1571 | `ProjectToolsService::saveChangeRequest` | **update** [project_change_requests](/code/tables/project_change_requests.md) (`title`, `description`, `reason`, `impact_days`, `impact_cost`, `impact_scope`, `updated_datetime`) |  |
| 1577 | `ProjectToolsService::saveChangeRequest` | read [project_change_requests](/code/tables/project_change_requests.md) |  |
| 1578 | `ProjectToolsService::saveChangeRequest` | **insert** [project_change_requests](/code/tables/project_change_requests.md) (`project_id`, `number`, `title`, `description`, `reason`, `impact_days`, `impact_cost`, `impact_scope`, `status`, `raised_by_id`, `raised_datetime`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### decideChangeRequest

*public static* - line 1599

Approve or reject a change request. Approving takes a new baseline - the changed plan becomes the agreed one - and, when project_change_apply is "plan", first moves the target finish by the days and adds the cost as a budget line, recording both in `applied`. Rejecting changes nothing.

```php
function decideChangeRequest(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    int $crId,
    string $decision,
    $notes
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$crId` | `int` |  | the change request (project_change_requests.id) to decide; must still be 'proposed' |
| `$decision` | `string` |  | 'approved' or 'rejected' |
| `$notes` |  |  | the decision notes (cut to 20000 characters); null or blank for none |

**Returns:** array{baseline_id:?int, applied:?array}

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1616 | `ProjectToolsService::decideChangeRequest` | **update** [project_change_requests](/code/tables/project_change_requests.md) (`status`, `decided_by_id`, `decided_datetime`, `decision_notes`, `updated_datetime`) |  |
| 1626 | `ProjectToolsService::decideChangeRequest` | **update** [projects](/code/tables/projects.md) (`target_end_date`) |  |
| 1635 | `ProjectToolsService::decideChangeRequest` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1636 | `ProjectToolsService::decideChangeRequest` | **insert** [project_budget_lines](/code/tables/project_budget_lines.md) (`project_id`, `title`, `category`, `planned_amount`, `notes`, `position`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 1645 | `ProjectToolsService::decideChangeRequest` | **update** [project_change_requests](/code/tables/project_change_requests.md) (`baseline_id`, `applied`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### withdrawChangeRequest

*public static* - line 1666

Withdraw a request still waiting for a decision: whoever raised it, or the project's team.

```php
function withdrawChangeRequest(PDO $conn, ActorContext $ctx, int $projectId, int $crId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$crId` | `int` |  | the change request (project_change_requests.id) to withdraw |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1672 | `ProjectToolsService::withdrawChangeRequest` | **update** [project_change_requests](/code/tables/project_change_requests.md) (`status`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### changeRequest

*private static* - line 1682

Loads one change request of the project; throws not found otherwise.

```php
function changeRequest(PDO $conn, int $projectId, int $crId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$crId` | `int` |  | the change request (project_change_requests.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1685 | `ProjectToolsService::changeRequest` | read [project_change_requests](/code/tables/project_change_requests.md) |  |

### changeable

*private static* - line 1700

Loads the project for the actor and checks they may change it; throws otherwise.

```php
function changeable(PDO $conn, ActorContext $ctx, int $projectId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

### member

*private static* - line 1712

Loads one member row of the project; throws not found otherwise.

```php
function member(PDO $conn, int $projectId, int $memberId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$memberId` | `int` |  | the member (project_members.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1714 | `ProjectToolsService::member` | read [project_members](/code/tables/project_members.md) |  |

### item

*private static* - line 1726

Loads one deliverable of the project; throws not found otherwise.

```php
function item(PDO $conn, int $projectId, int $itemId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the deliverable (project_items.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1728 | `ProjectToolsService::item` | read [project_items](/code/tables/project_items.md) |  |

### moscow

*private static* - line 1740

A validated MoSCoW value in lower case, or null for none.

```php
function moscow($v): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | must, should, could or wont in any case; null or '' means none |

### stageOf

*private static* - line 1753

A validated stage id of this project, or null for none.

```php
function stageOf(PDO $conn, int $projectId, $stageId): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$stageId` |  |  | a stage id as sent; null, '' or 0 means none |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1756 | `ProjectToolsService::stageOf` | read [project_stages](/code/tables/project_stages.md) |  |

### roleId

*private static* - line 1767

A validated project role id, or null for none.

```php
function roleId(PDO $conn, $v): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$v` |  |  | a project role id (project_roles.id) as sent; null, '' or 0 means none |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1770 | `ProjectToolsService::roleId` | read [project_roles](/code/tables/project_roles.md) |  |

### roleName

*private static* - line 1781

The name of a project role, or null.

```php
function roleName(PDO $conn, ?int $id): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `?int` |  | the project role id; null or 0 gives null |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1784 | `ProjectToolsService::roleName` | read [project_roles](/code/tables/project_roles.md) |  |

### memberName

*private static* - line 1794

The name of a project member - analyst, team, portal user or contractor - or #id.

```php
function memberName(PDO $conn, int $memberId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$memberId` | `int` |  | the member (project_members.id) to name |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1797 | `ProjectToolsService::memberName` | read [project_members](/code/tables/project_members.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md), read [users](/code/tables/users.md) |  |

### mustExist

*private static* - line 1811

Runs a one-id lookup query and throws a validation error with `$msg` when it finds nothing.

```php
function mustExist(PDO $conn, string $sql, int $id, string $msg): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$sql` | `string` |  | a developer-written query with one ? placeholder that returns a row when the id is valid |
| `$id` | `int` |  | the id to look up |
| `$msg` | `string` |  | the validation message shown when nothing is found |

### str

*private static* - line 1824

Trimmed text cut to `$max` characters, or null when blank.

```php
function str($v, int $max): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the text as sent; null or blank gives null |
| `$max` | `int` |  | the most characters to keep |

### src

*private static* - line 1832

The history source for the actor: 'api' or 'app'.

```php
function src(ActorContext $ctx): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |

### saveBudgetLine

*public static* - line 1847

Create (no id) or update a budget line. Returns its id.

```php
function saveBudgetLine(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (to change one), title (required), category, planned, actual, forecast (amounts), planned_date, spent_date, contract_id (linked to the project), cost_centre_id, notes (up to 500) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1880 | `ProjectToolsService::saveBudgetLine` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1886 | `ProjectToolsService::saveBudgetLine` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1889 | `ProjectToolsService::saveBudgetLine` | **update** [project_budget_lines](/code/tables/project_budget_lines.md) (`title`, `category`, `planned_amount`, `actual_amount`, `contract_id`, `cost_centre_id`, `notes`, `updated_datetime`) |  |
| 1894 | `ProjectToolsService::saveBudgetLine` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1895 | `ProjectToolsService::saveBudgetLine` | **insert** [project_budget_lines](/code/tables/project_budget_lines.md) (`project_id`, `title`, `category`, `planned_amount`, `actual_amount`, `contract_id`, `cost_centre_id`, `notes`, `position`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md)

### budgetLineWhen

*private static* - line 1915

The 3.3.0 columns, on their own: before Database Verification adds them the rest of the line still saves.

```php
function budgetLineWhen(
    PDO $conn,
    int $lineId,
    ?string $plannedDate,
    ?string $spentDate,
    ?float $forecast
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$lineId` | `int` |  | the budget line (project_budget_lines.id) |
| `$plannedDate` | `?string` |  | when the money is due to go out (YYYY-MM-DD), or null |
| `$spentDate` | `?string` |  | when it went out (YYYY-MM-DD), or null |
| `$forecast` | `?float` |  | what the line is now expected to cost, or null |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1918 | `ProjectToolsService::budgetLineWhen` | **update** [project_budget_lines](/code/tables/project_budget_lines.md) (`planned_date`, `spent_date`, `forecast_amount`) |  |

### deleteBudgetLine

*public static* - line 1929

Deletes a budget line, records it in the project's history, and checks health and tolerances again.

```php
function deleteBudgetLine(PDO $conn, ActorContext $ctx, int $projectId, int $lineId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$lineId` | `int` |  | the budget line (project_budget_lines.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1932 | `ProjectToolsService::deleteBudgetLine` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 1936 | `ProjectToolsService::deleteBudgetLine` | **delete** [project_budget_lines](/code/tables/project_budget_lines.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md)

### addProjectRate

*public static* - line 1950

The project's own hourly rate from a date (labour mode "rate"), in the project's currency. Each rate is a new row: the old one still prices the time logged before the new one's date.

```php
function addProjectRate(PDO $conn, ActorContext $ctx, int $projectId, $rate, ?string $from): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$rate` |  |  | the hourly rate as typed, in the project's currency |
| `$from` | `?string` |  | the date it applies from (YYYY-MM-DD); null or '' means today |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1960 | `ProjectToolsService::addProjectRate` | **insert** [project_labour_rates](/code/tables/project_labour_rates.md) (`scope`, `ref_id`, `hourly_rate`, `effective_from`, `created_by_id`, `created_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### deleteProjectRate

*public static* - line 1972

Remove one of the project's own rates (entered by mistake).

```php
function deleteProjectRate(PDO $conn, ActorContext $ctx, int $projectId, string $from): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$from` | `string` |  | the effective-from date (YYYY-MM-DD) of the project's rate to remove |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1976 | `ProjectToolsService::deleteProjectRate` | **delete** [project_labour_rates](/code/tables/project_labour_rates.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### setCurrency

*public static* - line 1989

Change the project's currency - a RELABEL: amounts are not converted, and the page says so before anyone presses Save. Only when Projects -> Settings -> Budget lets projects choose.

```php
function setCurrency(PDO $conn, ActorContext $ctx, int $projectId, string $code): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$code` | `string` |  | a three-letter currency code such as GBP (any case) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1998 | `ProjectToolsService::setCurrency` | **update** [projects](/code/tables/projects.md) (`currency`, `updated_datetime`) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### announce

*public static* - line 2020

Announce planned disruption from a project - "Move day: phones down Saturday". How depends on Projects -> Settings -> General: planned planned maintenance: upcoming until its start, then an incident now the same, starting now - an incident straight away off refused (the button is not shown) Needs Service Status as well as being allowed to change the project. Service Status is install-wide, like contracts: no company check on the services. Returns the planned maintenance id.

```php
function announce(PDO $conn, ActorContext $ctx, int $projectId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | title, comment, start and end (as `statusPlannedUtc()` reads them; start is ignored in 'now' mode), services - a list of {service_id, impact_level_id} |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2024 | `ProjectToolsService::announce` | read [projects](/code/tables/projects.md) |  |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### withdrawAnnouncement

*public static* - line 2049

Withdraw an announcement that has not started. It must belong to this project.

```php
function withdrawAnnouncement(PDO $conn, ActorContext $ctx, int $projectId, int $plannedId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$plannedId` | `int` |  | the planned maintenance (status_planned.id) to withdraw; must belong to this project |

**Called from:** [api/projects/tools.php](/code/files/api/projects/tools.php.md)

### announcements

*public static* - line 2067

What the Connections tab needs: the mode, this project's announcements, and the services and impact levels for the dialog. null when the analyst cannot open Service Status - then the panel is not drawn at all.

```php
function announcements(PDO $conn, int $analystId, int $projectId): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 2076 | `ProjectToolsService::announcements` | read [status_services](/code/tables/status_services.md) |  |
| 2077 | `ProjectToolsService::announcements` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

**Called from:** [api/projects/get.php](/code/files/api/projects/get.php.md), [api/projects/tools.php](/code/files/api/projects/tools.php.md)

