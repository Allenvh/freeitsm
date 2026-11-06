# wiki_files

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `scan_id` | `INT NOT NULL` |  |
| `file_path` | `VARCHAR(500) NOT NULL` |  |
| `file_name` | `VARCHAR(255) NOT NULL` |  |
| `folder_path` | `VARCHAR(500) NOT NULL` |  |
| `file_type` | `VARCHAR(10) NOT NULL` |  |
| `file_size_bytes` | `BIGINT NOT NULL DEFAULT 0` |  |
| `line_count` | `INT NOT NULL DEFAULT 0` |  |
| `last_modified` | `DATETIME NULL` |  |
| `description` | `LONGTEXT NULL` |  |
| `created_date` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/wiki/get_file_detail.php](/code/files/api/wiki/get_file_detail.php.md) | L28, L48, L58 |  |
| [api/wiki/get_files.php](/code/files/api/wiki/get_files.php.md) | L36 |  |
| [api/wiki/get_folder_tree.php](/code/files/api/wiki/get_folder_tree.php.md) | L32 |  |
| [api/wiki/get_function_detail.php](/code/files/api/wiki/get_function_detail.php.md) | L28, L42 |  |
| [api/wiki/get_stats.php](/code/files/api/wiki/get_stats.php.md) | L33, L37, L40, L44 |  |
| [api/wiki/get_table_references.php](/code/files/api/wiki/get_table_references.php.md) | L38 |  |
| [api/wiki/get_tables_list.php](/code/files/api/wiki/get_tables_list.php.md) | L31 |  |
| [api/wiki/search.php](/code/files/api/wiki/search.php.md) | L44, L54, L66 |  |

