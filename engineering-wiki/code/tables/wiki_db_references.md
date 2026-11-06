# wiki_db_references

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `file_id` | `INT NOT NULL` |  |
| `table_name` | `VARCHAR(255) NOT NULL` |  |
| `reference_type` | `VARCHAR(50) NOT NULL` |  |
| `line_number` | `INT NULL` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/wiki/get_file_detail.php](/code/files/api/wiki/get_file_detail.php.md) | L68 |  |
| [api/wiki/get_stats.php](/code/files/api/wiki/get_stats.php.md) | L33 |  |
| [api/wiki/get_table_references.php](/code/files/api/wiki/get_table_references.php.md) | L38 |  |
| [api/wiki/get_tables_list.php](/code/files/api/wiki/get_tables_list.php.md) | L31 |  |
| [api/wiki/search.php](/code/files/api/wiki/search.php.md) | L66 |  |

