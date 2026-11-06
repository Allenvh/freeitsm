# includes/cost_centre_assign.php

> PHP - 176 lines

Charging things to a cost centre (GH #160, stage 2) - the rules, ONCE.

Assets, contracts and people (users) each carry cost_centre_id. Every one of
them belongs to a company (NULL = the Default company), and a cost centre
belongs to exactly one company (cost_centres.tenant_id, never NULL). So:

  🔑 A record can only be charged to a cost centre of ITS OWN company.
  🔑 Only an ACTIVE one can be chosen - inactive means "not for new
     assignments" (Daniel's words in #160). A record already charged to one
     that has since been switched off KEEPS it, and may be saved unchanged.

The screens, the REST API, the asset import and the contract-text migration
all come through `costCentreCheck()` / `costCentreByCode()`, so the rule cannot
differ between them. Pickers list `costCentreOptions()`, which is the same set
plus the record's current one when that is inactive - so it never vanishes
from the box it is shown in.

Deleting a cost centre is refused while anything is charged to it - see
`costCentreUsage()` and CostCentresService::delete(). Retire, don't delete.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [costCentreReady()](/code/functions/costCentreReady.md) | 32 | Has Database Verification added cost_centre_id to `$table` yet? |
| [costCentreRecordTenant()](/code/functions/costCentreRecordTenant.md) | 45 | A record's company for cost centre purposes: its tenant_id, or Default when NULL. |
| [costCentreOptions()](/code/functions/costCentreOptions.md) | 57 | What a picker offers for a record in company `$tenantId`: its active cost centres, plus `$keepId` when that is the record's current one and inactive. |
| [costCentreCheck()](/code/functions/costCentreCheck.md) | 78 | Validate a cost centre being given to a record in company `$tenantId`. Returns the id to store (null = none). Throws ServiceError otherwise. `$currentId` is what the record holds now: keeping an inactive one is fine. |
| [costCentreByCode()](/code/functions/costCentreByCode.md) | 103 | A cost centre of company `$tenantId` by its CODE (ignoring case), or null. |
| [costCentreRef()](/code/functions/costCentreRef.md) | 118 | {id, code, name} for display and the API, or null. |
| [costCentreSelectSql()](/code/functions/costCentreSelectSql.md) | 134 | Columns for any SELECT over a table with cost_centre_id, alias `$a`: cost_centre_id and cost_centre_label ("CODE Name"). NULLs before Verification. |
| [costCentreLabel()](/code/functions/costCentreLabel.md) | 146 | "CODE Name", or '' - one display form everywhere. |
| [costCentreUsage()](/code/functions/costCentreUsage.md) | 158 | How many things are charged to cost centre `$id`, per kind, leaving out the zeros. Empty means it is free to delete. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `costCentreOptions` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 85 | `costCentreCheck` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 107 | `costCentreByCode` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 121 | `costCentreRef` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 137 | `costCentreSelectSql` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 162 | `costCentreUsage` | read [assets](/code/tables/assets.md) |  |
| 163 | `costCentreUsage` | read [contracts](/code/tables/contracts.md) |  |
| 164 | `costCentreUsage` | read [users](/code/tables/users.md) |  |
| 165 | `costCentreUsage` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md)

**Included by:** [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md), [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md), [api/contracts/get_contract.php](/code/files/api/contracts/get_contract.php.md), [api/contracts/get_contracts.php](/code/files/api/contracts/get_contracts.php.md), [api/cost-centres/options.php](/code/files/api/cost-centres/options.php.md), [api/tickets/get_person.php](/code/files/api/tickets/get_person.php.md), [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md), [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md), [includes/leases.php](/code/files/includes/leases.php.md), [includes/people.php](/code/files/includes/people.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md), [includes/services/contracts.php](/code/files/includes/services/contracts.php.md), [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md)

**Calls:** [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

