# includes/sla_notifications.php

> PHP - 414 lines

SLA Breach Notification Engine

Called by `cron/sla_breach_check.php` on a schedule (every 5 mins recommended).
Walks every open SLA-enabled ticket, decides whether warning / breach
notifications should fire for response and / or resolution targets, resolves
the matching rule's recipients, and sends the email via the ticket's
mailbox (Microsoft Graph or Gmail). Dedups via `sla_notifications_sent`
so the same notification fires at most once per (ticket, target, trigger).

Public entry: sla_run_breach_check(PDO `$conn`): array
  Returns a summary { sent: [...], skipped: [...], errors: [...] } for
  cron logging.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sla_run_breach_check()](/code/functions/sla_run_breach_check.md) | 24 | Main cron entry. Returns a summary so the cron wrapper can log it. |
| [sla_emit_workflow_event()](/code/functions/sla_emit_workflow_event.md) | 142 | Emit the time-based workflow event for an SLA warning / breach. |
| [sla_find_matching_rule()](/code/functions/sla_find_matching_rule.md) | 213 | Find the most-specific rule matching this ticket + trigger + target. |
| [sla_resolve_recipients()](/code/functions/sla_resolve_recipients.md) | 241 | Expand a rule into a unique list of recipient email addresses. Resolves assignee, department-team members, named analyst, and the explicit emails list; dedupes case-insensitively. |
| [sla_send_breach_email()](/code/functions/sla_send_breach_email.md) | 300 | Send a breach / warning email via the ticket's originating mailbox. Falls back to the first active mailbox if the ticket has none (e.g. manually-created ticket). |
| [sla_build_breach_email_body()](/code/functions/sla_build_breach_email_body.md) | 339 | Render the HTML body for a breach / warning email. |
| [sla_notification_already_sent()](/code/functions/sla_notification_already_sent.md) | 389 | Check if we've already fired this notification for (ticket, target, trigger). |
| [sla_mark_notification_sent()](/code/functions/sla_mark_notification_sent.md) | 402 | Records that an SLA notification went out for a ticket, so it is not sent twice. |
| [sla_get_first_active_mailbox()](/code/functions/sla_get_first_active_mailbox.md) | 408 | The active mailbox with the lowest id, decrypted; null when there is none. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `sla_run_breach_check` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 157 | `sla_emit_workflow_event` | read [tickets](/code/tables/tickets.md) |  |
| 170 | `sla_emit_workflow_event` | read [emails](/code/tables/emails.md) |  |
| 214 | `sla_find_matching_rule` | read [tickets](/code/tables/tickets.md) |  |
| 220 | `sla_find_matching_rule` | read [sla_notification_rules](/code/tables/sla_notification_rules.md) |  |
| 245 | `sla_resolve_recipients` | read [tickets](/code/tables/tickets.md), read [analysts](/code/tables/analysts.md) |  |
| 257 | `sla_resolve_recipients` | read [tickets](/code/tables/tickets.md), read [department_teams](/code/tables/department_teams.md), read [analyst_teams](/code/tables/analyst_teams.md), read [analysts](/code/tables/analysts.md) |  |
| 272 | `sla_resolve_recipients` | read [analysts](/code/tables/analysts.md) |  |
| 390 | `sla_notification_already_sent` | read [sla_notifications_sent](/code/tables/sla_notifications_sent.md) |  |
| 403 | `sla_mark_notification_sent` | **insert** [sla_notifications_sent](/code/tables/sla_notifications_sent.md) (`ticket_id`, `target_type`, `trigger_type`, `recipients`) |  |
| 409 | `sla_get_first_active_mailbox` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

## How it hangs together

**Includes:** [includes/sla.php](/code/files/includes/sla.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md)

**Included by:** [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md)

**Calls:** [buildTicketMergeData()](/code/functions/buildTicketMergeData.md), [decryptMailboxRow()](/code/functions/decryptMailboxRow.md), [internalTicketEmail()](/code/functions/internalTicketEmail.md), [sla_format_minutes()](/code/functions/sla_format_minutes.md), [sla_get_state()](/code/functions/sla_get_state.md), [sla_load_settings()](/code/functions/sla_load_settings.md), [workflowEmitOnce()](/code/functions/workflowEmitOnce.md)

