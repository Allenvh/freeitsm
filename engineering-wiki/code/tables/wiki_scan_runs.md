# wiki_scan_runs

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `started_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `completed_at` | `DATETIME NULL` |  |
| `status` | `VARCHAR(20) NOT NULL DEFAULT 'running'` |  |
| `files_scanned` | `INT NOT NULL DEFAULT 0` |  |
| `functions_found` | `INT NOT NULL DEFAULT 0` |  |
| `classes_found` | `INT NOT NULL DEFAULT 0` |  |
| `error_message` | `LONGTEXT NULL` |  |
| `scanned_by` | `VARCHAR(100) NULL` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/wiki/get_files.php](/code/files/api/wiki/get_files.php.md) | L25 |  |
| [api/wiki/get_folder_tree.php](/code/files/api/wiki/get_folder_tree.php.md) | L20 |  |
| [api/wiki/get_scan_history.php](/code/files/api/wiki/get_scan_history.php.md) | L20 |  |
| [api/wiki/get_stats.php](/code/files/api/wiki/get_stats.php.md) | L21 |  |
| [api/wiki/get_table_references.php](/code/files/api/wiki/get_table_references.php.md) | L27 |  |
| [api/wiki/get_tables_list.php](/code/files/api/wiki/get_tables_list.php.md) | L20 |  |
| [api/wiki/search.php](/code/files/api/wiki/search.php.md) | L29 |  |

