# api/self-service/get_team_tickets.php

> API endpoint - 153 lines

API: the tickets a MANAGER may see - raised by the people they manage (discussion #62, step 3). GET, same row shape as get_tickets.php plus who raised each one.

?page=N          50 at a time, newest activity first
  ?person=<id>     only this person's tickets (must be on the team)
  ?status=<name>   only tickets in this status

Also returns `people` and `statuses` - each with a count - for the two
dropdowns, so they list only what is there, whatever page is loaded.

🔑 PAGED ON THE SERVER. It used to stop at the 300 most recent tickets, which
a head of department with a big team outgrows in weeks, and filtering by
person or status in the browser only ever filtered those 300.

🔑 The rules `portalTicketAccess()` applies to a list - same company as the
manager (no company = Default), and confidential tickets left out when
managers may see nothing of them - are ALSO written into the query, so that a
page is a full page and the counts are true. Every row then still goes through
`portalTicketAccess()` itself, the same rule the ticket page, the attachments and
the recordings use: if the two ever disagreed, a page would come back a row
short - never a row too long.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_team_tickets.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET person`, `GET status`, `GET page` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 65 |  | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |
| 81 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 95 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 101 |  | read [emails](/code/tables/emails.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [departments](/code/tables/departments.md), read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/managers.php](/code/files/includes/managers.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [managerCompany()](/code/functions/managerCompany.md), [managerRow()](/code/functions/managerRow.md), [managersSettings()](/code/functions/managersSettings.md), [managerTeamUserIds()](/code/functions/managerTeamUserIds.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md), [ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

