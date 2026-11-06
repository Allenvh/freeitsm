# includes/services/cost_centres.php

> PHP - 560 lines

CostCentresService - the rules for cost centres (GH #160, stage 1), ONCE.

Shared by System -> Cost Centres (api/system/cost_centres.php), its spreadsheet
import, and the REST API (api/v1/resources/cost_centres.php). Each caller
passes an ActorContext; this layer validates, writes and returns, or throws
ServiceError. It never emits HTTP.

─────────────────────────────────────────────────────────────────────────────
WHAT A COST CENTRE IS HERE

A code, a name, an optional description, an optional parent (the hierarchy)
and active/inactive. Always ONE company's: two companies can both have 0010.

🔑 THE CODE IS TEXT. "0010" and "N1414" are both real codes. It is trimmed and
nothing else - never cast, never padded, never upper-cased. It is unique per
company IGNORING CASE (the column's collation does that, and so does every
comparison in here), so n1414 and N1414 are the same cost centre, not two.

🔑 THE CODE IS THE KEY FOR SYNCING. Most organisations keep the real list in
their accounting/ERP system and copy it in. `sync()` matches on code, so the
same file or API call can be sent every night: new codes are added, changed
ones updated, and nothing is duplicated.

🔑 INACTIVE MEANS "NOT FOR NEW ASSIGNMENTS". Whatever is already charged to an
inactive cost centre keeps it (stage 2). Retiring, not deleting, is the normal
path; delete is for a mistake, and is refused while it has children.

─────────────────────────────────────────────────────────────────────────────
COMPANIES

Every by-id method starts with `load()`, which 404s a cost centre in a company
the caller cannot reach - never 403, so ids cannot be probed. A parent must
be in the same company, and can never be the cost centre itself or anything
below it (that would be a loop, and a loop has no top).

## Classes

- [CostCentresService](/code/classes/CostCentresService.md) (class, line 42)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 | `CostCentresService::ready` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 113 | `CostCentresService::listForCompany` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 133 | `CostCentresService::load` | read [cost_centres](/code/tables/cost_centres.md), read [tenants](/code/tables/tenants.md) |  |
| 169 | `CostCentresService::create` | **insert** [cost_centres](/code/tables/cost_centres.md) (`tenant_id`, `code`, `name`, `description`, `parent_id`, `is_active`) |  |
| 205 | `CostCentresService::update` | **update** [cost_centres](/code/tables/cost_centres.md) |  |
| 237 | `CostCentresService::delete` | **delete** [cost_centres](/code/tables/cost_centres.md) |  |
| 399 | `CostCentresService::sync` | **insert** [cost_centres](/code/tables/cost_centres.md) (`tenant_id`, `code`, `name`, `description`, `is_active`) |  |
| 407 | `CostCentresService::sync` | **update** [cost_centres](/code/tables/cost_centres.md) |  |
| 414 | `CostCentresService::sync` | **update** [cost_centres](/code/tables/cost_centres.md) (`parent_id`) |  |
| 421 | `CostCentresService::sync` | **update** [cost_centres](/code/tables/cost_centres.md) (`is_active`) |  |
| 513 | `CostCentresService::assertCodeFree` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 531 | `CostCentresService::resolveParentInput` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 536 | `CostCentresService::resolveParentInput` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 549 | `CostCentresService::resolveParentInput` | read [cost_centres](/code/tables/cost_centres.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md)

**Included by:** [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md), [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md)

**Calls:** [costCentreUsage()](/code/functions/costCentreUsage.md), [getAllTenants()](/code/functions/getAllTenants.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [tenancyTablesReady()](/code/functions/tenancyTablesReady.md)

