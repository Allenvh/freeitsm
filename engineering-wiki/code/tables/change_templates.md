# change_templates

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(200) NOT NULL` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `change_type_id` | `INT NULL` |  |
| `priority_id` | `INT NULL` |  |
| `impact_id` | `INT NULL` |  |
| `category_id` | `INT NULL` |  |
| `risk_likelihood` | `TINYINT NULL` |  |
| `risk_impact_score` | `TINYINT NULL` |  |
| `description_template` | `LONGTEXT NULL` |  |
| `reason_template` | `LONGTEXT NULL` |  |
| `risk_template` | `LONGTEXT NULL` |  |
| `test_plan_template` | `LONGTEXT NULL` |  |
| `rollback_plan_template` | `LONGTEXT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

*No SQL in the scanned code names this table directly.*
