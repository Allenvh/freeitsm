# api/domains/tick.php

> API endpoint - 20 lines

POST — the page-load run for installs with no cron. The Domains pages call this in the background, never waiting on it; it does a short batch of lookups, checks and alerts at most once an hour (see `domainOpportunisticRun()`) and otherwise returns at once.

⚠️ session was opened read-and-close by the bootstrap, so a slow run here
never holds the session lock against the page the analyst is looking at.

## The endpoint

| | |
|---|---|
| URL | `api/domains/tick.php` |
| Protected by | Module access: domains (requireModuleAccessJson) (via includes/domains/api_bootstrap.php) |

## How it hangs together

**Includes:** [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md)

**Calls:** [domainApiOk()](/code/functions/domainApiOk.md), [domainApiRun()](/code/functions/domainApiRun.md), [domainOpportunisticRun()](/code/functions/domainOpportunisticRun.md)

