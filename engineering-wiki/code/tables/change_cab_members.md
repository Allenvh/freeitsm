# change_cab_members

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `change_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `is_required` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `vote` | `VARCHAR(20) NULL` |  |
| `vote_comment` | `TEXT NULL` |  |
| `vote_datetime` | `DATETIME NULL` |  |
| `added_by_id` | `INT NULL` |  |
| `added_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):506 | `ChangesService::saveCab` | insert | `change_id`, `analyst_id`, `is_required`, `added_by_id`, `added_datetime` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):512 | `ChangesService::saveCab` | update | `is_required` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):520 | `ChangesService::saveCab` | delete |  |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):557 | `ChangesService::voteCab` | update | `vote`, `vote_comment`, `vote_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L103 |  |
| [api/change-management/get_approvals.php](/code/files/api/change-management/get_approvals.php.md) | L32, L95, L119 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangeCabGet L451 |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md) | ChangesService::saveCab L490, ChangesService::voteCab L546, ChangesService::voteCab L590 |  |

