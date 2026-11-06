# change_notifications

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `change_id` | `INT NOT NULL` |  |
| `notification_type` | `VARCHAR(50) NOT NULL` |  |
| `message` | `VARCHAR(500) NOT NULL` |  |
| `is_read` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

*No SQL in the scanned code names this table directly.*
