# change_categories

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(255) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L34 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangeSelect L57, apiChangeCategoriesList L537 |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md) | ChangesService::createChange L74, ChangesService::updateChange L226, ChangesService::loadJoined L746 |  |

