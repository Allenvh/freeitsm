# api/projects/tools.php

> API endpoint - 217 lines

Projects - the tools on a project (3.2.0 phase 2): people, scope / MoSCoW and the RACI matrix. Every rule lives in includes/services/project_tools.php; this only routes.

GET  ?project_id=N&people=Q          people from People in the project's company, for the picker
     ?project_id=N&target_options=1  types, statuses, locations and fields for the target dialog (Assets access)
     ?project_id=N&target_preview=1&<rule fields>  {done, total} for a rule being edited (Assets access)
     ?project_id=N&target_assets=ID&show=left|done  the assets behind a target, at most 200 (Assets access)
POST {action, project_id, ...}
     member_add     {analyst_id | team_id | user_id, role_id?, notes?}
     member_update  {member_id, role_id?, notes?, power?, interest?, stance?, keep_informed?}   (stakeholder map 3.3.0)
     member_remove  {member_id}
     item_save      {id?, title, description?, acceptance_criteria?, moscow?, stage_id?, status?}
     item_delete    {id}
     item_move      {id, moscow, order:[ids in that column]}
     raci_set       {item_id, member_id, letter: R|A|C|I|''}
     raid_save      {id?, type, title, description?, probability?, impact?, response?, response_plan?, owner_analyst_id?, status?, due_date?, ticket_id?}
     raid_delete    {id}
     raid_to_knowledge {id}   a lesson -> a draft Knowledge article (Knowledge access)
     raid_to_ticket    {id}   an issue -> a new ticket (Tickets access)
     raid_escalate     {id, note}   (3.3.0)
     raid_deescalate   {id}
     raid_action_add   {id, title, assigned_analyst_id?, due_date?}   a new project task, linked
     raid_action_remove {id, task_id}   unlinks; the task stays
     tolerances_save {time?: days|null, risk?: score|null, cost?: percent|null (3.3.0)}
     gate_decide    {stage_id, decision: go|go_with_conditions|stop, notes?}
     target_save    {id?, name, scope: filter|linked, scope_type_id?, scope_field?, scope_value?, done_field, done_op, done_value, target_date?}
     target_delete  {id}
     announce       {title, comment?, start?, end?, services:[{service_id, impact_level_id}]}  (Service Status)
     announce_withdraw {id}
     budget_line_save {id?, title, category, planned?, actual?, contract_id?, cost_centre_id?, notes?, planned_date?, spent_date?, forecast? (3.3.0)}
     budget_line_delete {id}
     budget_rate_add {rate, from?}      the project's own hourly rate (labour mode 'rate')
     budget_rate_delete {from}
     budget_currency {currency}         relabel - only when projects may choose
     milestone_save {id?, name?, due_date?, stage_id?, notes?, done?, done_date?}   (3.3.0)
     milestone_delete {id}
     task_dates     {task_id, start_date?, due_date?}   the Timeline moving a bar (3.3.0)
     task_estimate  {task_id, estimate_hours}   the Plan's estimate box (3.3.0)
     baseline_take  {label?}   (3.3.0 change control - includes/projects/control.php)
     change_save    {id?, title, description?, reason?, impact_days?, impact_cost?, impact_scope?}
     change_decide  {id, decision: approved|rejected, notes?}
     change_withdraw {id}
     dep_add        {task_id, depends_on_id, lag_days?}   (3.3.0) task_id waits for depends_on_id; no loops
     dep_remove     {id}
     gate_item_save {id?, stage_id, kind: check|document|signoff|change, title, analyst_id?, change_id?, notes?}   (3.3.0 gate checklists)
     gate_item_delete {id}
     gate_item_tick {id, done?, document_id?, notes?}   a sign-off by its named analyst only; a change item cannot be ticked
     gate_kind      {stage_id, kind: standard|golive}   go-live adds the starter items
     benefit_save   {id?, title, measure?, unit?, direction: up|down, baseline_value?, target_value?, target_date?, owner_analyst_id?, review_date?, review_months?, status: open|closed, notes?}   (3.3.0)
     benefit_delete {id}
     benefit_measure_add {id, value, measured_date?, note?}
     benefit_measure_delete {id, measure_id}
     proposal_decide {decision: approved|rejected, notes?}   (3.3.0 intake - includes/projects/intake.php; notes required to reject)

## The endpoint

| | |
|---|---|
| URL | `api/projects/tools.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |
| Reads | `GET project_id`, `GET people`, `GET target_options`, `GET target_preview`, `GET target_assets`, `GET show` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 69 |  | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/projects/targets.php](/code/files/includes/projects/targets.php.md)

**Calls:** [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [projectApiBody()](/code/functions/projectApiBody.md), [projectApiFail()](/code/functions/projectApiFail.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectTargetAssets()](/code/functions/projectTargetAssets.md), [projectTargetCount()](/code/functions/projectTargetCount.md), [projectTargetOptions()](/code/functions/projectTargetOptions.md)

