# notifications

In-app notifications (discussion #55).

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `event_type` | `VARCHAR(64) NOT NULL` |  |
| `entity_type` | `VARCHAR(32) NOT NULL` |  |
| `entity_id` | `INT NOT NULL` |  |
| `entity_ref` | `VARCHAR(64) NULL` |  |
| `title` | `VARCHAR(255) NULL` |  |
| `body` | `VARCHAR(500) NULL` |  |
| `actor_name` | `VARCHAR(100) NULL` |  |
| `event_count` | `INT NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `read_datetime` | `DATETIME NULL` |  |

*No SQL in the scanned code names this table directly.*
