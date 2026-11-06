# api/tickets/request_csat.php

> API endpoint - 69 lines

API: Manually request a CSAT survey for a ticket.

Used by the "Request feedback" toolbar button. Bypasses the auto-trigger
(which only fires on close transitions) and the one-per-ticket guard (an
analyst clicking the button is a deliberate request, not survey-spam).

Refuses if csat_mode is 'off' — settings are still the master switch.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/request_csat.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/csat.php](/code/files/includes/csat.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [csatGetSetting()](/code/functions/csatGetSetting.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sendCsatSurvey()](/code/functions/sendCsatSurvey.md)

