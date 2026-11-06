# cron/domains.php

> PHP - 87 lines

Domains — cron entry point (#154).

Refreshes registry details, runs the DNS / email-security / certificate
checks, runs the optional Certificate Transparency and look-alike watches,
then sends whatever alerts are due. See includes/domains/scheduler.php.

HOURLY is the right schedule. Each run works for up to four minutes on the
domains that most need it (never looked up, oldest check first), so a large
register is covered across a few runs; alerts are fire-once, so running more
often never sends anything twice.

  Linux    0 * * * *  /usr/bin/php /path/to/cron/domains.php
  Windows  Task Scheduler, hourly, running php.exe with the full script path
  HTTP     https://your-freeitsm/cron/domains.php?token=…

Without a cron the module still works: opening Domains runs a short batch at
most once an hour (Domains → Settings → Monitoring). That is a fallback, not a
scheduler — if nobody opens the module, nothing is refreshed.

SECURITY (HTTP only): ?token= must match the encrypted `domain_cron_token`
setting (seeded by Database Verification; `php scripts/cron_token.php` prints
it). No token configured means the HTTP door is SHUT. CLI needs no token.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [domainScheduledRun()](/code/functions/domainScheduledRun.md)

