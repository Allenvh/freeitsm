# includes/inbox_display.php

> PHP - 205 lines

What each ticket row in the inbox middle pane shows, and how.

── Why this is a setting rather than a decision ─────────────────────────────

GitHub discussion #61 asked for two things: the assigned agent on the row, and
a coloured priority indicator. The request was to REPLACE the description
preview with "Assigned to: …", which is the one thing not done here — that line
is the only content signal in the row, and it is how you tell "printer jammed
again" from something that needs reading now.

Everything else is offered rather than chosen, because row density is genuinely
personal: a manager scanning a queue for breaches wants different chips from
somebody working one department all day. Hence per-analyst, over an install
default an administrator sets for everyone who has not chosen.

── The one real design constraint ───────────────────────────────────────────

⚠️ THE ROW ALREADY HAS A COLOURED DOT, AND IT ALREADY MEANS SOMETHING.
`.email-sla-pill` carries an SLA dot in red / amber / green. Priority in a
traffic-light palette is the obvious second dot and would be actively harmful:
red would mean "about to breach" in one place and "Critical" in another, in the
same row, and those call for different actions.

That is why STRIPE and BLOCK exist as styles at all, and why the shipped
default puts priority on the left edge rather than in the footer. Anyone who
prefers a dot can still choose one — they just have to choose it.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [inboxDisplayRegistry()](/code/functions/inboxDisplayRegistry.md) | 42 | The registry: every field that can appear on a row, and the styles it accepts. |
| [inboxDisplayDefaults()](/code/functions/inboxDisplayDefaults.md) | 90 | The shipped default, used when neither the install nor the analyst has chosen. |
| [inboxDisplayNormalise()](/code/functions/inboxDisplayNormalise.md) | 108 | Coerce anything into a valid config. |
| [inboxDisplayInstallDefault()](/code/functions/inboxDisplayInstallDefault.md) | 132 | The install-wide default an administrator has set, or the shipped one. |
| [inboxDisplayForAnalyst()](/code/functions/inboxDisplayForAnalyst.md) | 154 | What THIS analyst should see: their own choice, else the install default. |
| [inboxDisplayIsPersonal()](/code/functions/inboxDisplayIsPersonal.md) | 189 | Has this analyst chosen for themselves, or are they following the install default? |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 135 | `inboxDisplayInstallDefault` | read [system_settings](/code/tables/system_settings.md) |  |
| 164 | `inboxDisplayForAnalyst` | read [user_preferences](/code/tables/user_preferences.md) |  |
| 196 | `inboxDisplayIsPersonal` | read [user_preferences](/code/tables/user_preferences.md) |  |

## How it hangs together

**Included by:** [api/tickets/save_inbox_display.php](/code/files/api/tickets/save_inbox_display.php.md), [tickets/index.php](/code/files/tickets/index.php.md), [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)

