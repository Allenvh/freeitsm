# apikeys

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `apikey` | `VARCHAR(50) NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `label` | `VARCHAR(100) NULL` |  |
| `datestamp` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `active` | `TINYINT(1) NULL DEFAULT 1` |  |
| `tenant_id` | `INT NULL` | Multi-tenancy: the company an ingest key belongs to (NULL = Default). |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/software/delete_apikey.php](/code/files/api/software/delete_apikey.php.md):30 |  | delete |  |  |
| [api/software/generate_apikey.php](/code/files/api/software/generate_apikey.php.md):30 |  | insert | `apikey`, `analyst_id`, `label` |  |
| [api/software/toggle_apikey.php](/code/files/api/software/toggle_apikey.php.md):29 |  | update | `active` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/external/device-manager/submit/index.php](/code/files/api/external/device-manager/submit/index.php.md) | L48, L52 |  |
| [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md) | L60, L64 |  |
| [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md) | L51, L55 |  |
| [api/software/get_apikeys.php](/code/files/api/software/get_apikeys.php.md) | L21 |  |
| [api/software/toggle_apikey.php](/code/files/api/software/toggle_apikey.php.md) | L35 |  |
| [api/watchtower/get_dashboard_ext.php](/code/files/api/watchtower/get_dashboard_ext.php.md) | L53 |  |

