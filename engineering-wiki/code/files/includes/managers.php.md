# includes/managers.php

> PHP - 485 lines

Managers in the self-service portal (discussion #62, step 3).

A MANAGER is a portal user who may read the tickets raised by other portal
users - "the Head of Logistics sees Logistics' tickets". This file is the ONE
place that decides who that is; every portal endpoint asks
`portalTicketAccess()` and nothing else, so an attachment link, a document or a
recording can never be a way round a rule the ticket page keeps.

WHERE MANAGEMENT COMES FROM
---------------------------
 1. The directory: users.manager_id, filled from AD's manager attribute by
    directory sync. Someone's line manager can see their tickets - switchable
    (managers_directory), direct reports only or the whole chain below.
 2. Management lines set up in FreeITSM (`manager_grants`), any mix of:
       everyone | a person | a people group | a department name | their reports
    plus EXCLUSIONS (a person, a group, a department). An exclusion always
    wins, whatever granted the access - including the directory - because
    "everyone in Sales except the person raising a grievance about me" is the
    case the exclusion exists for.

THE RULES (agreed with Ed)
--------------------------
 - OFF until switched on (managers_enabled), and FAIL CLOSED: before Database
   Verification has added the sensitivity and view columns, nobody is a
   manager, because a confidential ticket could not be told apart.
 - Same company only, both ways: the person must be in the manager's company
   - where NO company means the Default company, the documented convention
   for every row a company owns (Multi-Tenancy-Isolation). Somebody who has
   never set up companies, or never filed these people, must not have to do
   anything extra to make managers work (Ed) -
   AND so must the ticket (a ticket can be moved to another company).
 - A manager who has left sees nothing. Whether managers keep seeing the
   tickets of people who have left is a setting, on by default - an open
   ticket still needs oversight after its requester goes (Ed).
 - Confidential tickets: nothing (default), a stub, or everything
   (managers_confidential).
 - What a manager may DO - reply, close - is a setting; viewing is the floor.
 - Department names are free text (users.department), matched ignoring case
   and surrounding spaces. A renamed department therefore loses its line;
   System -> Managers warns about lines that match nobody.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [managersResetMemo()](/code/functions/managersResetMemo.md) | 59 | Clears the per-request managers cache, so settings saved mid-request are read again. |
| [managersSettings()](/code/functions/managersSettings.md) | 62 | The settings, with their defaults. |
| [managersActive()](/code/functions/managersActive.md) | 94 | Can managers work at all on this install? Needs the switch on AND every table the rules depend on - fail closed otherwise. |
| [managerRow()](/code/functions/managerRow.md) | 113 | The manager's own row, or null if they cannot be a manager (missing, or left). |
| [managerTeamUserIds()](/code/functions/managerTeamUserIds.md) | 132 | The people a manager may see the tickets of, as user ids. Memoised per request. Empty when managers are off, the manager has left, or - on a multi-company install - the manager belongs to no company. |
| [managerGrantMembers()](/code/functions/managerGrantMembers.md) | 182 | The people one line refers to, within the base (company, not-self, leavers). Used for both grants and exclusions. |
| [managerReachSql()](/code/functions/managerReachSql.md) | 240 | Everyone a line could ever reach for this manager, as an SQL condition on `users u`: same company (no company = Default), not the manager themselves, and - unless the setting keeps them - not people who have left. The ONE definition; the team, and System -> Managers' warnings, both use it. |
| [managerCompany()](/code/functions/managerCompany.md) | 263 | The company a row belongs to, where NO company (NULL) means the Default company - the convention every company-owned row follows. A ticket nobody has routed and a person nobody has filed are both Default's. |
| [managerHasTeam()](/code/functions/managerHasTeam.md) | 273 | Does this portal user manage anybody? Decides whether "Team tickets" is shown. |
| [managerTeamPreviewIds()](/code/functions/managerTeamPreviewIds.md) | 287 | The team as it WOULD be with managers switched on - for the admin screens, so an admin can check who sees what before turning the portal over. Only the master switch is pretended; a missing table still gives nobody. The memo is put back afterwards, so nothing else in the request sees the pretence. |
| [managerLinesEditable()](/code/functions/managerLinesEditable.md) | 306 | May the signed-in analyst add or remove management lines? System -> Managers decides: administrators only (the default), or anyone who can edit people - the caller has already required Tickets or Assets. Being able to reach the manager (analystCanAccessUser) is checked by the caller as well. |
| [portalTicketAccess()](/code/functions/portalTicketAccess.md) | 324 | THE question every portal endpoint asks: may this portal user see this ticket, and as whom? |
| [managersNotifyNewTicket()](/code/functions/managersNotifyNewTicket.md) | 385 | Tell the requester's managers that a new ticket has been raised, as System -> Managers says: 'none' (the default), 'email', or 'bell' - the self-service portal's own notification bell. |
| [managersEmailNewTicket()](/code/functions/managersEmailNewTicket.md) | 459 | The email version. Sent through the portal's own sender (the ticket mailbox, as account emails are), to the manager's address - none, nothing sent. The link is built on the configured public address, because a ticket that arrives by email is created from cron, where there is no request to take a host from. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 | `managersSettings` | read [system_settings](/code/tables/system_settings.md) |  |
| 101 | `managersActive` | read [manager_grants](/code/tables/manager_grants.md) |  |
| 115 | `managerRow` | read [users](/code/tables/users.md) |  |
| 143 | `managerTeamUserIds` | read [manager_grants](/code/tables/manager_grants.md) |  |
| 191 | `managerGrantMembers` | read [users](/code/tables/users.md) |  |
| 193 | `managerGrantMembers` | read [users](/code/tables/users.md) |  |
| 198 | `managerGrantMembers` | read [users](/code/tables/users.md), read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md), read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 208 | `managerGrantMembers` | read [users](/code/tables/users.md) |  |
| 219 | `managerGrantMembers` | read [users](/code/tables/users.md) |  |
| 328 | `portalTicketAccess` | read [tickets](/code/tables/tickets.md) |  |
| 393 | `managersNotifyNewTicket` | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |
| 405 | `managersNotifyNewTicket` | read [users](/code/tables/users.md) |  |
| 415 | `managersNotifyNewTicket` | read [manager_grants](/code/tables/manager_grants.md) |  |
| 461 | `managersEmailNewTicket` | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/ticket_views.php](/code/files/includes/ticket_views.php.md), [includes/services/notifications.php](/code/files/includes/services/notifications.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md)

**Included by:** [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md), [api/self-service/get_attachment.php](/code/files/api/self-service/get_attachment.php.md), [api/self-service/get_document.php](/code/files/api/self-service/get_document.php.md), [api/self-service/get_recording.php](/code/files/api/self-service/get_recording.php.md), [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md), [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md), [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md), [api/system/managers.php](/code/files/api/system/managers.php.md), [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md), [includes/ticket_events.php](/code/files/includes/ticket_events.php.md), [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)

**Calls:** [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md), [ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md), [ticketViewsReady()](/code/functions/ticketViewsReady.md)

