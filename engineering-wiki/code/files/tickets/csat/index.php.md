# tickets/csat/index.php

> PHP - 485 lines

CSAT Analytics — dedicated page for CSAT KPIs, distribution, per-analyst breakdown, and recent responses with comments. Reachable via Tickets > nav.

v1 ships as a standalone page rather than as a dashboard widget because
the existing widget library models "group X by Y" and doesn't fit CSAT's
average-of-ratings semantics cleanly. Can be migrated to the widget library
later if it proves useful enough to deserve the plumbing.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 100 |  | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md), read [tickets](/code/tables/tickets.md) |  |
| 121 |  | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md), read [tickets](/code/tables/tickets.md) |  |
| 135 |  | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md), read [tickets](/code/tables/tickets.md), read [analysts](/code/tables/analysts.md) |  |
| 155 |  | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md), read [tickets](/code/tables/tickets.md) |  |
| 166 |  | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md), read [tickets](/code/tables/tickets.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md) |  |
| 184 |  | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md), read [tickets](/code/tables/tickets.md), read [analysts](/code/tables/analysts.md) |  |
| 195 |  | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md), read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [tickets/includes/header.php](/code/files/tickets/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [fmt_datetime()](/code/functions/fmt_datetime.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

