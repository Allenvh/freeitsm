# ticket_presence

Collision detection (#934): who is looking at which ticket right now. Ephemeral by nature — one row per (ticket, analyst), refreshed by a heartbeat and meaningless once `last_seen` goes stale. Deliberately NOT an audit trail: rows are overwritten and deleted freely.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `last_seen` | `DATETIME NULL` |  |
| `is_composing` | `TINYINT(1) NOT NULL DEFAULT 0` | 1 while the reply/forward/note composer is open — the state worth warning about, as opposed to merely having the ticket on screen. |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md):82 | `presenceHeartbeat` | insert | `ticket_id`, `analyst_id`, `last_seen`, `is_composing` |  |
| [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md):135 | `presenceLeave` | delete |  |  |
| [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md):139 | `presenceLeave` | delete |  |  |
| [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md):156 | `presencePurge` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md) | presenceOthers L103 |  |

