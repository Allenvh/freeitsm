# includes/ticket_snooze.php

> PHP - 327 lines

Snoozing a ticket — "I can't do anything with this until Thursday."

WHY THIS EXISTS
---------------
Half of a service desk's queue at any moment is waiting on somebody else: the
user who hasn't sent the screenshot, the supplier who ships on Tuesday, the
change that goes in at the weekend. Those tickets are not work — but they sit
in the inbox looking exactly like work, so an analyst re-reads them every
morning to re-decide they still can't be touched. Snoozing takes the ticket
out of the queue and brings it back at the moment it becomes actionable.

WAKING IS THE CLOCK, NOT A CRON
-------------------------------
A snoozed ticket is defined as `snoozed_until > `UTC_TIMESTAMP()`` and nothing
else. Every list, count and badge tests that expression, so a ticket comes
back the instant its time passes whether or not anything is scheduled to run.
This is deliberate: an install with the crons switched off (or a box that was
asleep) must never leave a ticket buried past its wake time. The columns are
left holding a past value once it expires — harmless, and it means the audit
trail keeps the record of what was asked for.

SNOOZE IS ON THE TICKET, NOT ON THE ANALYST
-------------------------------------------
Gmail-style snooze is personal, because an email belongs to one person. A
ticket doesn't: "waiting on the user until Thursday" is a fact about the
ticket, true for everyone who looks at it. So it sleeps for the whole desk,
and the banner names who put it to sleep and why, which is the information a
colleague needs when they wonder where it went.

WHAT IT DOES NOT DO
-------------------
It does not touch SLA. The clock keeps running on a sleeping ticket, because
the customer's target is not suspended by the desk deciding to look away —
pausing it here would let a queue hit 100% by snoozing its way out of trouble.
It does not change status, and it does not hide the ticket from search.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [snoozeSchemaReady()](/code/functions/snoozeSchemaReady.md) | 56 | Does this database have the snooze columns yet? |
| [scheduleSchemaReady()](/code/functions/scheduleSchemaReady.md) | 79 | Does this database have the scheduling columns yet? (#1161) |
| [snoozeWakeHour()](/code/functions/snoozeWakeHour.md) | 98 | The hour "Tomorrow" and "Next week" land on — Tickets → Settings → General, default 09:00. Interpreted in the *snoozing analyst's* timezone, so a desk spread across zones each gets their own start of day rather than the server's. |
| [snoozeHiddenSql()](/code/functions/snoozeHiddenSql.md) | 121 | SQL fragment hiding sleeping tickets. Append to any WHERE (or LEFT JOIN ON) that lists or counts the working queue. No placeholders, so it never disturbs a caller's parameter ordering — the same property that lets the trashed-ticket predicate be appended to `$ttSql` in get_ticket_counts.php. |
| [snoozeOnlySql()](/code/functions/snoozeOnlySql.md) | 136 | The complement: only sleeping tickets. Drives the Snoozed folder and its count. Note the pre-upgrade answer is `1=0` and NOT the empty string — an empty fragment here would turn "only the sleeping ones" into "every ticket you own", which is the one failure mode worse than the folder simply being empty. |
| [resolveSnoozeUntil()](/code/functions/resolveSnoozeUntil.md) | 156 | Turn a preset (or a custom local datetime) into the UTC instant to store. |
| [snoozeTicket()](/code/functions/snoozeTicket.md) | 223 | Put one ticket to sleep. Caller has already checked access. Deliberately does NOT bump updated_datetime — snoozing is the desk deciding not to act, not activity on the ticket, and shoving it to the top of a sort by last-updated on its way out of the list would be a lie. |
| [wakeTicket()](/code/functions/wakeTicket.md) | 242 | Wake a ticket early. Returns true if it was actually asleep, so a caller can stay quiet rather than announcing a wake that woke nothing. |
| [wakeSnoozedTicketOnCustomerReply()](/code/functions/wakeSnoozedTicketOnCustomerReply.md) | 273 | The customer came back while the ticket was asleep — wake it. |
| [snoozeStateFor()](/code/functions/snoozeStateFor.md) | 287 | Read the sleep state of one ticket for the reading pane: null when it is awake, otherwise the wake time (UTC), who put it there and why. |
| [snoozeAudit()](/code/functions/snoozeAudit.md) | 319 | Best-effort trail entry. Never fails the action it is recording. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `snoozeSchemaReady` | read [tickets](/code/tables/tickets.md) |  |
| 83 | `scheduleSchemaReady` | read [tickets](/code/tables/tickets.md) |  |
| 102 | `snoozeWakeHour` | read [system_settings](/code/tables/system_settings.md) |  |
| 228 | `snoozeTicket` | **update** [tickets](/code/tables/tickets.md) (`snoozed_until`, `snoozed_at`, `snoozed_by`, `snooze_reason`) |  |
| 246 | `wakeTicket` | read [tickets](/code/tables/tickets.md) |  |
| 252 | `wakeTicket` | **update** [tickets](/code/tables/tickets.md) (`snoozed_until`, `snoozed_at`, `snoozed_by`, `snooze_reason`) |  |
| 291 | `snoozeStateFor` | read [tickets](/code/tables/tickets.md), read [analysts](/code/tables/analysts.md) |  |
| 322 | `snoozeAudit` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md)

**Included by:** [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md), [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md), [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md), [api/tickets/get_scheduled_tickets.php](/code/files/api/tickets/get_scheduled_tickets.php.md), [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md), [api/tickets/snooze_ticket.php](/code/files/api/tickets/snooze_ticket.php.md), [api/tickets/wake_ticket.php](/code/files/api/tickets/wake_ticket.php.md), [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), [tickets/index.php](/code/files/tickets/index.php.md)

