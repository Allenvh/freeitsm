# wiki_classes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `file_id` | `INT NOT NULL` |  |
| `class_name` | `VARCHAR(255) NOT NULL` |  |
| `line_number` | `INT NOT NULL` |  |
| `extends_class` | `VARCHAR(255) NULL` |  |
| `implements_interfaces` | `LONGTEXT NULL` |  |
| `description` | `LONGTEXT NULL` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/wiki/get_file_detail.php](/code/files/api/wiki/get_file_detail.php.md) | L43 |  |

