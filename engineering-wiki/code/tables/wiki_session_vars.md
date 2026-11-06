# wiki_session_vars

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `file_id` | `INT NOT NULL` |  |
| `variable_name` | `VARCHAR(255) NOT NULL` |  |
| `access_type` | `VARCHAR(10) NOT NULL` |  |
| `line_number` | `INT NULL` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/wiki/get_file_detail.php](/code/files/api/wiki/get_file_detail.php.md) | L73 |  |

