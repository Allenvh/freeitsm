# includes/report_packs/blocks_assets.php

> PHP - 157 lines

Report Packs: Assets and Intune blocks.

Assets are scoped to the pack's company (assets.tenant_id). Intune devices have
no company - Intune is connected once for the whole install - so the Intune
blocks report the install's devices, as the Intune dashboard does.

Both are snapshots of now, except where a block is about dates (warranty and
lease expiry, which use the pack's range).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rpAssetsBreakdown()](/code/functions/rpAssetsBreakdown.md) | 39 | Assets block: a chart of how many assets there are, grouped by the chosen dimension. A snapshot of today, not the period. |
| [rpAssetsKpis()](/code/functions/rpAssetsKpis.md) | 59 | Assets block: KPI tiles - total assets, assets added in the period, warranties ending in the period and assets already out of warranty. |
| [rpAssetsExpiring()](/code/functions/rpAssetsExpiring.md) | 84 | Assets whose warranty and/or lease ends inside the range. |
| [rpIntuneBreakdown()](/code/functions/rpIntuneBreakdown.md) | 123 | Intune block: a chart of Intune devices grouped by the chosen dimension. A snapshot of today. |
| [rpIntuneKpis()](/code/functions/rpIntuneKpis.md) | 140 | Intune block: KPI tiles - device count, percentage compliant and encrypted, and devices not synced for 30 days. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 14 |  | read [asset_types](/code/tables/asset_types.md) |  |
| 15 |  | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 19 |  | read [asset_locations](/code/tables/asset_locations.md) |  |
| 43 | `rpAssetsBreakdown` | read [assets](/code/tables/assets.md) |  |
| 62 | `rpAssetsKpis` | read [assets](/code/tables/assets.md) |  |
| 90 | `rpAssetsExpiring` | read [assets](/code/tables/assets.md) |  |
| 126 | `rpIntuneBreakdown` | read [intune_devices](/code/tables/intune_devices.md) |  |
| 142 | `rpIntuneKpis` | read [intune_devices](/code/tables/intune_devices.md) |  |

## How it hangs together

**Included by:** [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md)

**Calls:** [rpChartFromRows()](/code/functions/rpChartFromRows.md), [rpFmtDate()](/code/functions/rpFmtDate.md), [rpTenantClause()](/code/functions/rpTenantClause.md), [t()](/code/functions/t.md)

