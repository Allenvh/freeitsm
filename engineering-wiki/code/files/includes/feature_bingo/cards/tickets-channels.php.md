# includes/feature_bingo/cards/tickets-channels.php

> PHP - 1020 lines

Feature Bingo cards - Tickets: the channels tickets arrive and leave by (mailboxes, WhatsApp, Slack, web chat), email and reply templates, reply cleanup AI, CSAT, the rota, and the working features outside Settings (Users, groups, dashboard, scheduling, snooze, merge/split, links, time). See includes/feature_bingo.php for the format.

Tickets -> Settings does not open a tab from the URL, so settings cards link to the
page itself.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 842 |  | read [tickets](/code/tables/tickets.md) |  |
| 1017 |  | read [system_settings](/code/tables/system_settings.md), read [target_mailboxes](/code/tables/target_mailboxes.md) |  |

