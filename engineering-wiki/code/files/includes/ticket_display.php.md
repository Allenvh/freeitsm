# includes/ticket_display.php

> PHP - 95 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Reading Long Tickets Developer Guide](https://github.com/edmozley/freeitsm/wiki/Reading-Long-Tickets-Developer-Guide)

How a ticket's messages are displayed — the collapsing of long ones.

From discussion #104: long email chains bury the thing you opened the ticket
to read. Same idea as Gmail's "···", and Zammad does it too.

─────────────────────────────────────────────────────────────────────────────
🔑 WHY HEIGHT AND NOT LINES

The request asked for a line threshold, and measuring real inbound mail says
that is the wrong unit. On a live install: 122 inbound emails, averaging 8,337
characters of which only ~1,800 are visible text — 38 of them laid out with
`<table>` and 26 carrying their own `<style>`. A vendor notification like
"Your Microsoft invoice is ready" is 57,750 characters and a handful of source
lines, and renders about a metre tall.

So the trigger is RENDERED HEIGHT, measured in the browser after the message
is laid out, which is the only number that corresponds to what somebody
actually has to scroll past. The SETTING is still expressed in lines, because
"collapse after about 12 lines" is a sentence an administrator can reason
about and "collapse after 260 pixels" is not — the conversion lives in one
place, below.

⚠️ Nothing here deletes anything. Collapsing is presentation: the full message
is in the page, one tap away, always. That matters because the quote-boundary
problem is genuinely hard — every mail client marks a quoted chain differently,
plenty do not mark it at all, and a forward can look identical to a reply — so
any approach is wrong some of the time, and a wrong boundary should cost a
click, never a fact.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ticketDisplaySettings()](/code/functions/ticketDisplaySettings.md) | 46 | The display settings, with defaults, in one place. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 76 | `ticketDisplaySettings` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Included by:** [tickets/index.php](/code/files/tickets/index.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

