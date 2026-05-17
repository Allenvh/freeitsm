# SLA Breach Notification Cron — Setup Guide

The SLA breach notification engine fires emails when tickets approach or exceed their
response / resolution SLA targets. Rules are configured under **Tickets → Settings → SLA →
Breach Notifications**, but the *trigger* is a scheduled task that runs the engine —
ideally every 5 minutes.

This document describes how to set up that schedule on Windows and Linux.

---

## What runs

A single PHP script: `cron/sla_breach_check.php`

It walks every open SLA-tracked ticket, decides whether each ticket's response /
resolution SLA has crossed the warning threshold or breached, finds the matching
notification rule, and sends emails via the ticket's originating mailbox (Microsoft
Graph or Gmail). De-duplication is automatic — once a notification has fired for a
given `(ticket, target, trigger)` it won't fire again.

Output is plain text — counts of sent / skipped / errors, plus one line per email
actually sent. Suitable for capturing in a Task Scheduler log or a cron-mailed report.

Recommended cadence: **every 5 minutes**. Frequent enough that breaches notify within
a useful window, sparse enough that you're not hammering the mailbox API.

---

## Two ways to invoke

### A. CLI (recommended)

`php c:\wamp64\www\freeitsm-app\cron\sla_breach_check.php`

No auth needed — filesystem permissions already gate who can run it.

### B. HTTP

`curl http://your-host/freeitsm-app/cron/sla_breach_check.php?token=<TOKEN>`

The token is auto-generated on first install and stored in `system_settings` under
the key `sla_cron_token`. Look it up with:

```sql
SELECT setting_value FROM system_settings WHERE setting_key = 'sla_cron_token';
```

Rotate it anytime by `UPDATE`-ing that row.

Use the HTTP form when you can't easily run PHP from the shell (some shared
hosting, or remote cron services like cron-job.org / EasyCron).

**Security layers on the HTTP form** (all enforced automatically):

1. **Shared-secret token** — 128 bits of entropy, compared with `hash_equals()`
   for constant-time matching.
2. **Per-IP failed-auth lockout** — >10 wrong-token attempts from the same IP
   in the past hour triggers a 1-hour 429 lockout. Defeats brute-force probing
   without needing fail2ban or similar.
3. **Min interval between successful runs** — `sla_cron_min_interval_seconds`
   in `system_settings` (default 30s). Even with a valid token, requests
   arriving sooner than this after the last successful run return 429. Defeats
   accidental double-scheduling, runaway loops, and post-token-leak abuse
   without affecting any reasonable schedule.

Every invocation (accepted or rejected) is logged to `sla_cron_runs`. Inspect
recent activity from the SLA settings tab ("Cron Activity" panel) or directly:

```sql
SELECT started_at, invocation, client_ip, outcome, sent_count, error_count, notes
  FROM sla_cron_runs
 ORDER BY started_at DESC
 LIMIT 20;
```

---

## Windows — Task Scheduler

Open Task Scheduler (`taskschd.msc`) and create a new task:

| Field | Value |
|-------|-------|
| Name | `FreeITSM — SLA Breach Check` |
| Run whether user is logged in or not | ✓ |
| Run with highest privileges | optional |
| Trigger | Daily, recur every **1 day**, repeat every **5 minutes** for **1 day** |
| Action | Start a program |
| Program/script | `C:\wamp64\bin\php\php8.2.x\php.exe` |
| Add arguments | `C:\wamp64\www\freeitsm-app\cron\sla_breach_check.php` |
| Start in | `C:\wamp64\www\freeitsm-app` |

(Adjust the PHP path to match your WAMP version — `C:\wamp64\bin\php\` lists what's installed.)

**Captured output**: Task Scheduler doesn't capture stdout by default. To log the
output, wrap the action in a tiny `.bat` file:

```bat
@echo off
"C:\wamp64\bin\php\php8.2.x\php.exe" "C:\wamp64\www\freeitsm-app\cron\sla_breach_check.php" >> "C:\wamp64\logs\sla_cron.log" 2>&1
```

…and point Task Scheduler at the `.bat` instead.

---

## Linux — cron

Add one line to your crontab (`crontab -e`):

```cron
*/5 * * * * /usr/bin/php /var/www/freeitsm-app/cron/sla_breach_check.php >> /var/log/freeitsm-sla-cron.log 2>&1
```

Adjust the `php` binary path (`which php`) and the FreeITSM install path to match your install.

Make sure the cron user has read access to `config.php` (DB credentials) and the
PHP install has the required extensions (`pdo_mysql`, `curl`, `mbstring`).

---

## Remote / hosted schedulers (cron-job.org, EasyCron, etc.)

Use the HTTP invocation:

```
GET https://your-itsm.example.com/cron/sla_breach_check.php?token=<TOKEN>
```

Set the interval to 5 minutes. The endpoint returns HTTP 200 on success and 500 on
error so the scheduler can alert you to failures.

---

## Verifying it works

After setting up the schedule:

1. Make sure SLA enforcement is enabled (`Tickets → Settings → SLA → Enforce SLAs from`
   set to a past datetime).
2. Have at least one ticket with a priority that has SLA targets set, and a
   notification rule that matches.
3. Run the script manually once:
   ```
   php c:\wamp64\www\freeitsm-app\cron\sla_breach_check.php --verbose
   ```
4. Look at the output. It will tell you which tickets it considered, which rules
   matched, and what was sent / skipped / errored.

Common skips and what they mean:
- `SLA enforcement disabled` — set `sla_enforce_from`.
- `no matching rule` — add a default rule (scope = Default) so every department is covered.
- `rule matched but no resolvable recipients` — the rule's assignee/team/analyst checkboxes
  are on but the ticket has no assignee, or the team has no members with email addresses.

---

## What gets de-duplicated

The `sla_notifications_sent` table records one row per `(ticket_id, target_type, trigger_type)`.
This means:

- A ticket's response warning fires **once**, even if the cron runs every 5 minutes for hours.
- Same ticket can also fire a response breach (separate row) and resolution warning / breach
  (separate rows).
- If you re-open a closed ticket, the rows persist — re-opens won't re-fire notifications.
  Clear the rows manually if you want them to re-fire:
  ```sql
  DELETE FROM sla_notifications_sent WHERE ticket_id = 12345;
  ```

---

## Troubleshooting

| Symptom | Likely cause |
|---------|--------------|
| Cron runs, output says "SLA enforcement disabled" | Set `Enforce SLAs from` in SLA settings |
| Cron runs, output says all tickets skipped with "no matching rule" | Add at least one default-scope rule |
| Cron runs, output says "rule matched but no resolvable recipients" | The rule's checkboxes don't yield any email addresses for these tickets — add explicit emails or a named analyst |
| HTTP invocation returns 503 with "Cron token not set" | Run `api/system/db_verify.php` to seed the token, or insert manually |
| HTTP invocation returns 403 | Token in URL doesn't match `sla_cron_token` in `system_settings` |
| HTTP invocation returns 429 "Rate limited" | A successful run completed less than `sla_cron_min_interval_seconds` ago (default 30s). Wait, or lower the setting |
| HTTP invocation returns 429 "Too many failed attempts" | This IP has hit >10 wrong-token attempts in the past hour. Wait 1 hour, or `DELETE FROM sla_cron_runs WHERE client_ip = '<ip>' AND outcome = 'auth_failed'` to clear |
| Emails not arriving | Check the originating mailbox is still authenticated (Tickets → Settings → Mailboxes); the cron uses the same OAuth tokens as inbound mail polling |

## Tuning settings

| Setting | Default | Effect |
|---------|---------|--------|
| `sla_cron_min_interval_seconds` | 30 | Minimum seconds between successful runs. Even a valid-token request gets a 429 if it arrives sooner |
| `sla_cron_log_retention_days` | 30 | How long to keep `sla_cron_runs` rows. Pruned automatically at end of every cron run |
| `sla_cron_token` | random per install | Shared secret for HTTP invocation. Rotate by `UPDATE`-ing this row |

Edit via SQL:
```sql
UPDATE system_settings SET setting_value = '60' WHERE setting_key = 'sla_cron_min_interval_seconds';
```
