# api/tickets/get_email_detail.php

> API endpoint - 335 lines

API Endpoint: Get email details Returns full email content for display in reading pane

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_email_detail.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET id`, `GET ticket_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [users](/code/tables/users.md) |  |
| 113 |  | read [emails](/code/tables/emails.md), read [tickets](/code/tables/tickets.md) |  |
| 118 |  | read [tickets](/code/tables/tickets.md), read [emails](/code/tables/emails.md) |  |
| 194 |  | **update** [emails](/code/tables/emails.md) (`is_read`) |  |
| 204 |  | read [problem_tickets](/code/tables/problem_tickets.md), read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 220 |  | read [change_tickets](/code/tables/change_tickets.md), read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 275 |  | read [ticket_merges](/code/tables/ticket_merges.md) |  |
| 306 |  | read [ticket_recordings](/code/tables/ticket_recordings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/ticket_links.php](/code/files/includes/ticket_links.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), [includes/ticket_split.php](/code/files/includes/ticket_split.php.md), [includes/ticket_views.php](/code/files/includes/ticket_views.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [integrationsLinksFor()](/code/functions/integrationsLinksFor.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [mergedAwayInfo()](/code/functions/mergedAwayInfo.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [resolveTenantIdForAddress()](/code/functions/resolveTenantIdForAddress.md), [scheduleSchemaReady()](/code/functions/scheduleSchemaReady.md), [snoozeStateFor()](/code/functions/snoozeStateFor.md), [splitInfoFor()](/code/functions/splitInfoFor.md), [ticketLinksFor()](/code/functions/ticketLinksFor.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md), [ticketViewRecord()](/code/functions/ticketViewRecord.md)

