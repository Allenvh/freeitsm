# ticket_prefixes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `prefix` | `VARCHAR(3) NOT NULL` |  |
| `description` | `VARCHAR(100) NULL` |  |
| `department_id` | `INT NULL` |  |
| `is_default` | `TINYINT(1) NULL DEFAULT 0` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

*No SQL in the scanned code names this table directly.*
