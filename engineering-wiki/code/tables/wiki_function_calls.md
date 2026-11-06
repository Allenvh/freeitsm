# wiki_function_calls

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `file_id` | `INT NOT NULL` |  |
| `function_name` | `VARCHAR(255) NOT NULL` |  |
| `line_number` | `INT NULL` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/wiki/get_function_detail.php](/code/files/api/wiki/get_function_detail.php.md) | L42 |  |

