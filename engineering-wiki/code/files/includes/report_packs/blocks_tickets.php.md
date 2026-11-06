# includes/report_packs/blocks_tickets.php

> PHP - 343 lines

Report Packs: Tickets blocks.

Same rules as the ticket dashboard (api/tickets/get_ticket_widget_data.php) so a
pack and the dashboard agree: trashed tickets never count, categories roll up to
their ROOT, and the empty buckets are named the same ("Unassigned", "Not
categorised"). What differs is the period: a dashboard says "last 30 days from
now", a pack says "1 to 30 September" and means it.

Three ways to pick tickets for a period (the `basis` option):
  created  created inside the range
  closed   closed inside the range
  open     still open at the END of the range - a snapshot, which is what
           "how big was the backlog on 30 September?" means

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rpTicketWhere()](/code/functions/rpTicketWhere.md) | 45 | WHERE for "tickets in this period, for this company, not trashed". |
| [rpTicketsBreakdown()](/code/functions/rpTicketsBreakdown.md) | 70 | Tickets block: a chart of tickets grouped by the chosen dimension (status, priority, category...). |
| [rpChartFromRows()](/code/functions/rpChartFromRows.md) | 94 | Turn [label, value, colour?] rows into chart data, folding everything past `limit` into one "Other" slice so a pie never has fifty slivers. |
| [rpTimeBuckets()](/code/functions/rpTimeBuckets.md) | 125 | Buckets for a time series over the range: day, week (Monday) or month, chosen from the range's length when 'auto'. Returned as local-date keys with labels. |
| [rpBucketKey()](/code/functions/rpBucketKey.md) | 150 | The bucket a date falls in: the Monday of its week, its month (Y-m) or its day. |
| [rpBucketCount()](/code/functions/rpBucketCount.md) | 165 | Count UTC timestamps into local buckets. |
| [rpTicketsTrend()](/code/functions/rpTicketsTrend.md) | 188 | Tickets block: a chart of tickets created and/or closed per day, week or month across the period. |
| [rpTicketsKpis()](/code/functions/rpTicketsKpis.md) | 216 | Tickets block: KPI tiles - created, closed, open at the end of the period, average time to close and first-time-fix rate. |
| [rpTicketsList()](/code/functions/rpTicketsList.md) | 254 | Tickets block: a table of the tickets in the period, with the columns the block asks for, up to its limit. |
| [rpFmtDateTime()](/code/functions/rpFmtDateTime.md) | 311 | Formats a UTC date and time in the viewer's own format and timezone; '' for none. |
| [rpFmtDate()](/code/functions/rpFmtDate.md) | 321 | A LOCAL Y-m-d (already in the viewer's zone) in their date format. |
| [rpHumanDuration()](/code/functions/rpHumanDuration.md) | 333 | A number of seconds as a short duration: 45s, 12m, 3h 5m, 2d 4h. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 23 |  | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 26 |  | read [departments](/code/tables/departments.md) |  |
| 27 |  | read [ticket_types](/code/tables/ticket_types.md) |  |
| 28 |  | read [analysts](/code/tables/analysts.md) |  |
| 29 |  | read [teams](/code/tables/teams.md) |  |
| 30 |  | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 31 |  | read [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |
| 35 |  | read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 75 | `rpTicketsBreakdown` | read [tickets](/code/tables/tickets.md) |  |
| 198 | `rpTicketsTrend` | read [tickets](/code/tables/tickets.md) |  |
| 220 | `rpTicketsKpis` | read [tickets](/code/tables/tickets.md) |  |
| 229 | `rpTicketsKpis` | read [tickets](/code/tables/tickets.md) |  |
| 257 | `rpTicketsList` | read [tickets](/code/tables/tickets.md) |  |
| 261 | `rpTicketsList` | read [analysts](/code/tables/analysts.md), read [ticket_categories](/code/tables/ticket_categories.md) |  |

## How it hangs together

**Included by:** [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md)

**Calls:** [fmt_datetime()](/code/functions/fmt_datetime.md), [rpTenantClause()](/code/functions/rpTenantClause.md), [t()](/code/functions/t.md)

