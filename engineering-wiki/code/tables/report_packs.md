# report_packs

Report Packs: a multi-page report designed in Reporting and exported as a PDF. The whole design (page setup, theme, header/footer/cover, criteria and every block with its options) is ONE JSON document, read and written whole by the designer - see includes/report_packs/. NULL never occurs: a new pack is saved with a starter design.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(200) NOT NULL` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `owner_id` | `INT NULL` |  |
| `design` | `LONGTEXT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `updated_by` | `INT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/reporting/packs/create.php](/code/files/api/reporting/packs/create.php.md):34 |  | insert | `name`, `description`, `owner_id`, `design`, `updated_datetime`, `updated_by` |  |
| [api/reporting/packs/delete.php](/code/files/api/reporting/packs/delete.php.md):19 |  | delete |  |  |
| [api/reporting/packs/save.php](/code/files/api/reporting/packs/save.php.md):40 |  | update | `name`, `description`, `design`, `updated_datetime`, `updated_by` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/reporting/packs/create.php](/code/files/api/reporting/packs/create.php.md) | L27 |  |
| [api/reporting/packs/get.php](/code/files/api/reporting/packs/get.php.md) | L18 |  |
| [api/reporting/packs/save.php](/code/files/api/reporting/packs/save.php.md) | L31 |  |
| [api/reporting/packs/shares.php](/code/files/api/reporting/packs/shares.php.md) | L25 |  |
| [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md) | rpRole L95, rpListPacks L134 |  |

