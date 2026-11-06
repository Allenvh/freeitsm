# software_inventory_log

Ingest log for software-inventory agent submissions — the submit endpoint always wrote here but the table was never defined (silent try/catch), so logging never worked. Defined 2026-07-03.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `host_id` | `INT NULL` |  |
| `api_response` | `LONGTEXT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md):124 | `logApiResponse` | insert | `host_id`, `api_response` |  |

