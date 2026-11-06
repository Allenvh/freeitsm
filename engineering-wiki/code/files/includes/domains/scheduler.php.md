# includes/domains/scheduler.php

> PHP - 173 lines

Domains — the scheduled run: keep every domain's facts fresh, then alert.

One function, three callers:

  cron/domains.php          hourly (recommended) — the reliable path
  api/domains/tick.php      a small run when somebody opens the module, at
                            most once an hour, for installs with no cron
  the settings screen       "Run now"

Everything works to a TIME BUDGET rather than a count. Lookups and checks
take a second or two per domain, and 300 domains is several minutes; a run
does what fits, oldest first, and the next run carries on. Nothing is ever
left half-written — each domain is finished or not started.

Order matters: lookups first (they move expiry dates), checks second (they
read what lookups wrote), alerts last (they read both).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainScheduledRun()](/code/functions/domainScheduledRun.md) | 37 | The Domains scheduled job, within a time budget - registry lookups that are due, daily health checks, the weekly certificate and look-alike watches, then alerts, the expiry calendar and Service Status. |
| [domainWatchDue()](/code/functions/domainWatchDue.md) | 127 | The weekly watches keep their "last scanned" in the domain's audit trail rather than two more columns: one row per scan, source 'check'. A domain with no such row in `$days` days is due. |
| [domainWatchMark()](/code/functions/domainWatchMark.md) | 148 | Records that a weekly watch ran for a domain today, keeping only the latest marker row. |
| [domainOpportunisticRun()](/code/functions/domainOpportunisticRun.md) | 160 | The page-load run. Throttled to once an hour, short, and silent: it runs inside a request somebody may be waiting on. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 | `domainScheduledRun` | read [domains](/code/tables/domains.md) |  |
| 71 | `domainScheduledRun` | read [domains](/code/tables/domains.md) |  |
| 130 | `domainWatchDue` | read [domains](/code/tables/domains.md), read [domain_audit](/code/tables/domain_audit.md) |  |
| 152 | `domainWatchMark` | **delete** [domain_audit](/code/tables/domain_audit.md) |  |
| 164 | `domainOpportunisticRun` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [includes/domains/settings.php](/code/files/includes/domains/settings.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md), [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/domains/watch.php](/code/files/includes/domains/watch.php.md), [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md), [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md)

**Included by:** [api/domains/settings.php](/code/files/api/domains/settings.php.md), [api/domains/tick.php](/code/files/api/domains/tick.php.md), [cron/domains.php](/code/files/cron/domains.php.md)

**Calls:** [domainAlertsRun()](/code/functions/domainAlertsRun.md), [domainCtScan()](/code/functions/domainCtScan.md), [domainLookalikeScan()](/code/functions/domainLookalikeScan.md), [domainLookup()](/code/functions/domainLookup.md), [domainLookupPace()](/code/functions/domainLookupPace.md), [domainSetting()](/code/functions/domainSetting.md), [domainSettings()](/code/functions/domainSettings.md), [domainSettingWrite()](/code/functions/domainSettingWrite.md), [domainStatusRun()](/code/functions/domainStatusRun.md), [domainSyncExpiryCalendar()](/code/functions/domainSyncExpiryCalendar.md)

