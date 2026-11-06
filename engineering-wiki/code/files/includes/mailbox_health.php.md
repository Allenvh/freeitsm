# includes/mailbox_health.php

> PHP - 168 lines

Mailbox health — everything that could be quietly wrong with a mailbox.

Built for #79, where a mailbox collected mail perfectly well but stamped no
origin on the tickets it opened. Nothing was broken enough to error, so
nothing said anything: the mailbox list showed a green "Authenticated" badge
and the fault only surfaced weeks later, on the tickets.

So the rule here is that a mailbox can be CONNECTED and still not be doing
what you think it is. Each check names one specific thing and says what the
consequence is, because "there is a problem" that doesn't say which problem
is barely better than silence.

Severity:
  'error'   — mail is not being collected, or is being collected wrongly.
  'warning' — mail is collected, but something downstream is not set up.

Pure function of a row already loaded by get_mailboxes.php (plus a couple of
looked-up extras), so it can't itself be the thing that breaks the list.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mailboxHealthProblems()](/code/functions/mailboxHealthProblems.md) | 40 | Every warning here can be dismissed, because every one of them can legitimately be somebody's deliberate choice: a mailbox that genuinely should record no origin, a receive-only IMAP mailbox with no outgoing server, a low-traffic mailbox nobody minds being checked rarely. Dismissing says "I know", and the item drops out of the ! while staying visible, and reversible, inside it. |

## How it hangs together

**Included by:** [api/tickets/get_mailboxes.php](/code/files/api/tickets/get_mailboxes.php.md), [api/tickets/set_mailbox_health_dismissed.php](/code/files/api/tickets/set_mailbox_health_dismissed.php.md)

