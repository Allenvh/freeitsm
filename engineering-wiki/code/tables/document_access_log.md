# document_access_log

Who fetched which document, and when. One row per download, written at the only place a document can actually be obtained — which is also the only place that can honestly claim to have recorded every access.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `document_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `action` | `VARCHAR(12) NOT NULL DEFAULT 'download'` |  |
| `ip_address` | `VARCHAR(45) NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/documents/download.php](/code/files/api/documents/download.php.md):66 |  | insert | `document_id`, `analyst_id`, `action`, `ip_address` |  |

