# External Issue Tracker Poll — Setup Guide

When a FreeITSM ticket is escalated to an issue tracker (Jira today), the ticket shows a pill with that issue's key, status and assignee. **That status only stays current if this scheduled task is running.** Without it the pill is frozen at whatever the issue's status was the moment it was raised.

Since #954 the same task also brings **comments** back from the tracker as internal notes, on connections where *Accept updates from Jira* is switched on. So an unscheduled poll now means two things never happen, not one.

This document describes how to set that schedule up on Windows and Linux.

> ⚠️ **This is currently the only way anything comes back.** Inbound webhooks — where the tracker pushes changes to us the moment they happen — are a later slice. Until then, if you do not schedule this, no status and no comment will ever reach a ticket.

---

## What runs

A single PHP script: `cron/integration_poll.php`

For every active connection that is due, it asks the tracker for the current state of the issues FreeITSM has links to, and updates any whose status, name or assignee has moved. Issues are read in **one batched query per connection**, not one call per ticket, so the cost is roughly constant however many tickets are linked.

Output is plain text — one line per connection, plus a line for every issue whose status *category* changed:

```
Our Jira (dev team)          checked  14, changed  2
    OPS-412  in_progress -> done
    OPS-418  todo -> in_progress
    comments: 3 seen, 2 imported (skipped: echo=1)
Acme client Jira             checked   3, changed  0

2 connection(s) polled, 17 issue(s) checked, 2 updated, 2 comment(s) imported, 0 failed.
```

**Recommended cadence: every 5 minutes.** Each connection also has its own **poll interval** (default 5 minutes) set under *System → Integrations*, so scheduling more often than that simply does nothing for connections that are not due — it is safe, just pointless. The `integration_cron_min_interval_seconds` setting (default 60s) stops accidental double-scheduling from hammering anyone's API.

### What it deliberately does not do

- **It never blanks a status.** If an issue is missing from the tracker's reply — deleted, or moved somewhere the token cannot see — the cached status is left alone rather than wiped. A stale value is more useful than a blank one, and a genuinely gone issue is better investigated than silently erased.
- **It stamps the attempt even when it fails**, so a permanently broken connection is retried on its normal interval rather than on every single run.
- **One broken tracker does not stop the others.** It is reported as `FAILED` with the tracker's own message and the run continues.
- **A tracker that is completely unreachable is reported, not hidden.** If no issues at all could be read, that is a failure — it does not quietly report "checked 12, changed 0" while your Jira has been down for a week.

---

## Two ways to invoke

### 1. Command line (preferred)

```
php /path/to/freeitsm/cron/integration_poll.php
```

No token is needed — there is no untrusted caller on the command line.

### 2. HTTP (for hosts with no shell access)

```
curl "https://your-install/cron/integration_poll.php?token=YOUR_TOKEN"
```

The token is `integration_cron_token` in `system_settings`, generated per install by **Database Verification** and encrypted at rest — read it with `php scripts/cron_token.php integration` rather than querying the row, which returns `ENC:…`. Without a matching token the endpoint returns `403`. Keep it out of anywhere public — anyone holding it can trigger polls.

---

## Windows (Task Scheduler)

```
schtasks /create /tn "FreeITSM Tracker Poll" /tr "\"C:\wamp64\bin\php\php8.4.0\php.exe\" \"C:\wamp64\www\freeitsm-app\cron\integration_poll.php\"" /sc minute /mo 5 /ru SYSTEM
```

## Linux (cron)

```
*/5 * * * * /usr/bin/php /var/www/freeitsm/cron/integration_poll.php >> /var/log/freeitsm-tracker-poll.log 2>&1
```

---

## Checking it is working

1. Run it once by hand and read the output — it names every connection it polled.
2. `No connections due.` means it ran but nothing had reached its interval yet. That is success, not a problem.
3. `Integration tables not present — nothing to poll.` means Database Verification has not been run yet.
4. `Rate limited.` means it ran more recently than `integration_cron_min_interval_seconds`. Harmless.
5. A connection line reading `FAILED:` carries the tracker's own error — bad credentials, a project that no longer exists, an unreachable host. That message is the tracker's, not ours, and is usually enough to fix it.

See also: `docs/webhook-cron-setup.md` and `docs/sla-cron-setup.md`, which follow the same token and interval conventions.
