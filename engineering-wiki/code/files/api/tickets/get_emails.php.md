# api/tickets/get_emails.php

> API endpoint - 257 lines

API Endpoint: Get list of emails Returns emails from the database for display in inbox

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_emails.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET department_id`, `GET status`, `GET assignee_id`, `GET team_id`, `GET trashed`, `GET snoozed` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 56 |  | read [emails](/code/tables/emails.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), [includes/inbox_view_filter.php](/code/files/includes/inbox_view_filter.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [inboxViewFilterFromRequest()](/code/functions/inboxViewFilterFromRequest.md), [inboxViewFilterSql()](/code/functions/inboxViewFilterSql.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [scheduleSchemaReady()](/code/functions/scheduleSchemaReady.md), [snoozeHiddenSql()](/code/functions/snoozeHiddenSql.md), [snoozeOnlySql()](/code/functions/snoozeOnlySql.md), [snoozeSchemaReady()](/code/functions/snoozeSchemaReady.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

