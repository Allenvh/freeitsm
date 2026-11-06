# api/domains/statuses.php

> API endpoint - 41 lines

GET — the domain statuses (open to anyone in the module: every form uses them). POST {action:'save', id?, name, colour, alerts_enabled, is_active, display_order} POST {action:'delete', id} Writes need Cap::DOMAINS_STATUSES — capabilities guard writes, never reads.

## The endpoint

| | |
|---|---|
| URL | `api/domains/statuses.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainSyncAfterStatusChange()](/code/functions/domainSyncAfterStatusChange.md) | 37 | A status switched to "no alerts" drops its domains from the calendar. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 13 |  | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md) |  |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md)

**Calls:** [domainApiBody()](/code/functions/domainApiBody.md), [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainRequireCap()](/code/functions/domainRequireCap.md), [domainSyncExpiryCalendar()](/code/functions/domainSyncExpiryCalendar.md)

