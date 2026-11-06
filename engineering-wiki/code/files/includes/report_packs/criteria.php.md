# includes/report_packs/criteria.php

> PHP - 153 lines

Report Packs: what a pack is ABOUT - its date range and its company.

Every block in a pack reads the same criteria, so "September" means the same
thirty days on the doughnut, the uptime list and the incident table. They are
set once on the pack (Data tab in the designer) and resolved here, on the server,
for whoever is looking - never trusted as SQL from the browser.

⚠️ A range is in the VIEWER'S timezone. "Last month" for an analyst in Santo
Domingo is 1 September 00:00 to 1 October 00:00 THERE, which is 04:00 UTC. The
database stores UTC (#126), so the bounds are converted before any query, and a
day near midnight lands in the month the reader would expect.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rpResolveRange()](/code/functions/rpResolveRange.md) | 35 | Resolve a pack's range into local dates (for display) and UTC bounds (for SQL). The end is EXCLUSIVE: [from, to). A custom range is inclusive of both days the reader picked, so its end is the day after. |
| [rpParseDate()](/code/functions/rpParseDate.md) | 92 | Parses a YYYY-MM-DD string as midnight in the given timezone; null when it is not a valid date. |
| [rpTenantClause()](/code/functions/rpTenantClause.md) | 115 | The company a pack reports on, as a WHERE fragment for `$qualified`. |
| [rpTenantLabel()](/code/functions/rpTenantLabel.md) | 143 | The company's name for the header field {{company}}. |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md)

**Included by:** [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md)

**Calls:** [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

