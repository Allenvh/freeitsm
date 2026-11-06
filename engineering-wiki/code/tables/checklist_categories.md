# checklist_categories

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [checklists/api.php](/code/files/checklists/api.php.md):77 |  | insert | `name` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [checklists/edit/index.php](/code/files/checklists/edit/index.php.md) | L48 |  |
| [checklists/index.php](/code/files/checklists/index.php.md) | L40 |  |
| [checklists/settings/index.php](/code/files/checklists/settings/index.php.md) | L66 |  |

