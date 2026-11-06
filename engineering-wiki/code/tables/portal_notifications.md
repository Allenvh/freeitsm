# portal_notifications

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `user_id` | `INT NOT NULL` |  |
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

## Read by

| File | Where | Purpose |
|---|---|---|
| [self-service/includes/header.php](/code/files/self-service/includes/header.php.md) | L307 |  |

