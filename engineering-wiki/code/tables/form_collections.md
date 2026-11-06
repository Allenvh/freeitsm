# form_collections

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(255) NOT NULL` |  |
| `description` | `TEXT NULL` |  |
| `closed_datetime` | `DATETIME NULL` |  |
| `closed_by` | `INT NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_date` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `modified_date` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):844 | `FormsService::saveCollection` | update | `name`, `description`, `modified_date` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):855 | `FormsService::saveCollection` | insert | `name`, `description`, `created_by`, `created_date`, `modified_date` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):878 | `FormsService::setCollectionClosed` | update | `closed_datetime`, `closed_by`, `modified_date` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):879 | `FormsService::setCollectionClosed` | update | `closed_datetime`, `closed_by`, `modified_date` |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md):911 | `FormsService::deleteCollection` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/forms/get_collection_submissions.php](/code/files/api/forms/get_collection_submissions.php.md) | L28 |  |
| [api/forms/get_forms.php](/code/files/api/forms/get_forms.php.md) | L50 |  |
| [includes/feature_bingo/cards/forms.php](/code/files/includes/feature_bingo/cards/forms.php.md) | L170 |  |
| [includes/services/forms.php](/code/files/includes/services/forms.php.md) | FormsService::listCollections L592, FormsService::setCollectionForms L647, FormsService::formsForPicker L721, FormsService::saveCollection L837, FormsService::saveCollection L847, FormsService::setCollectionClosed L884 ... |  |

