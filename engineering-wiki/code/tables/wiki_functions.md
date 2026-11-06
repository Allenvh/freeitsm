# wiki_functions

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `file_id` | `INT NOT NULL` |  |
| `function_name` | `VARCHAR(255) NOT NULL` |  |
| `line_number` | `INT NOT NULL` |  |
| `end_line_number` | `INT NULL` |  |
| `parameters` | `LONGTEXT NULL` |  |
| `class_name` | `VARCHAR(255) NULL` |  |
| `visibility` | `VARCHAR(20) NULL` |  |
| `is_static` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `description` | `LONGTEXT NULL` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/wiki/get_file_detail.php](/code/files/api/wiki/get_file_detail.php.md) | L38 |  |
| [api/wiki/get_files.php](/code/files/api/wiki/get_files.php.md) | L36 |  |
| [api/wiki/get_function_detail.php](/code/files/api/wiki/get_function_detail.php.md) | L28 |  |
| [api/wiki/search.php](/code/files/api/wiki/search.php.md) | L54 |  |

