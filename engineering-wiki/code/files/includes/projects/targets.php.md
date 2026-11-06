# includes/projects/targets.php

> PHP - 324 lines

Projects - asset targets (3.2.0): live progress measured from Assets.

A target is "these assets, and when each one counts as done":
  scope  - 'linked': the assets on the project's Connections tab, or
           'filter': every asset of a type and/or with a field containing a
           value - always inside the project's company;
  done   - one field, one operator, one value: status is Retired, operating
           system contains "Windows 11", feature release at least 24H2...

🔑 COUNTS ARE WORKED OUT ON READ, NEVER STORED - the same rule as task
progress. The moment somebody retires a laptop in Assets the project moves.
The snapshot table only keeps one point a day for the burn-up line.

🔑 THE SQL IS BUILT FROM WHITELISTS ONLY. Field names and operators come from
`projectTargetDoneFields()` / `projectTargetScopeFields()`; a value is always a
bound parameter. `projectTargetNormalise()` refuses anything else, and
`projectTargetSql()` asks it again rather than trusting a stored row.

Health: a live target that is past its date and not finished is red; one that
is more than 25 points behind a straight line from when it was set to its date
is amber. The project's automatic health takes the worse of tasks and targets
(projectAutoHealth).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectTargetsReady()](/code/functions/projectTargetsReady.md) | 31 | Has Database Verification created the tables? |
| [projectTargetDoneFields()](/code/functions/projectTargetDoneFields.md) | 45 | The fields a "done" rule can test: column, kind (list = an id from a list, text = free text) and the operators that make sense for it. |
| [projectTargetScopeFields()](/code/functions/projectTargetScopeFields.md) | 60 | The fields a filter scope can match with "contains". |
| [projectTargetNormalise()](/code/functions/projectTargetNormalise.md) | 71 | Check and tidy a target from the screen. Returns the row to store; throws ServiceError on anything the SQL builder would not accept. |
| [projectTargetSql()](/code/functions/projectTargetSql.md) | 120 | [FROM/WHERE for the assets in scope, args, the "is done" expression, its args] for one target on one project. Built from the whitelists only. |
| [projectTargetCount()](/code/functions/projectTargetCount.md) | 158 | [done, total] for one target. |
| [projectTargetHealth()](/code/functions/projectTargetHealth.md) | 175 | green \| amber \| red for a live target; null when there is nothing to measure. |
| [projectTargetsFor()](/code/functions/projectTargetsFor.md) | 194 | Every target on these projects with its counts and health: [project_id => [target, ...]]. Empty before Verification. |
| [projectTargetsWorst()](/code/functions/projectTargetsWorst.md) | 229 | The worst health across a project's targets, or null. |
| [projectTargetsDetail()](/code/functions/projectTargetsDetail.md) | 240 | For the project page: the targets, with today's point written and the last 120 days of points for the burn-up line, and the words for the rule. |
| [projectTargetValueNames()](/code/functions/projectTargetValueNames.md) | 265 | Names for list values in rules (status 6 => Retired), keyed "field:id". |
| [projectTargetAssets()](/code/functions/projectTargetAssets.md) | 288 | The assets still to do on a target (or all of them, `$done` = true), at most `$limit` - for the "what's left" list. Callers check Assets access first. |
| [projectTargetOptions()](/code/functions/projectTargetOptions.md) | 310 | The lists the target dialog offers, for the project's company. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 | `projectTargetsReady` | read [project_asset_targets](/code/tables/project_asset_targets.md) |  |
| 35 | `projectTargetsReady` | read [project_asset_target_snapshots](/code/tables/project_asset_target_snapshots.md) |  |
| 125 | `projectTargetSql` | read [project_assets](/code/tables/project_assets.md) |  |
| 199 | `projectTargetsFor` | read [project_asset_targets](/code/tables/project_asset_targets.md), read [projects](/code/tables/projects.md), read [asset_types](/code/tables/asset_types.md) |  |
| 245 | `projectTargetsDetail` | **insert** [project_asset_target_snapshots](/code/tables/project_asset_target_snapshots.md) (`target_id`, `snap_date`, `done`, `total`) |  |
| 247 | `projectTargetsDetail` | read [project_asset_target_snapshots](/code/tables/project_asset_target_snapshots.md) |  |
| 297 | `projectTargetAssets` | read [asset_status_types](/code/tables/asset_status_types.md), read [asset_locations](/code/tables/asset_locations.md) |  |
| 317 | `projectTargetOptions` | read [asset_types](/code/tables/asset_types.md) |  |
| 318 | `projectTargetOptions` | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 319 | `projectTargetOptions` | read [asset_locations](/code/tables/asset_locations.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/entity_links.php](/code/files/includes/entity_links.php.md)

**Included by:** [api/projects/tools.php](/code/files/api/projects/tools.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

**Calls:** [entityLink()](/code/functions/entityLink.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Traps

> [!WARNING]
> **Line 291, in `projectTargetAssets`:** an asset with no status makes "status = 6" NULL, not false, and NOT(NULL) is NULL too - the asset would vanish from "still to do". The count's CASE WHEN already treats NULL as not done; this must agree.

