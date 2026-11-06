# wiki_dependencies

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `file_id` | `INT NOT NULL` |  |
| `dependency_type` | `VARCHAR(50) NOT NULL` |  |
| `target_path` | `VARCHAR(500) NOT NULL` |  |
| `resolved_file_id` | `INT NULL` |  |
| `line_number` | `INT NULL` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/wiki/get_file_detail.php](/code/files/api/wiki/get_file_detail.php.md) | L48, L58 |  |
| [api/wiki/get_files.php](/code/files/api/wiki/get_files.php.md) | L36 |  |

