# analyst_signatures

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `body` | `LONGTEXT NOT NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/myaccount/delete_signature.php](/code/files/api/myaccount/delete_signature.php.md):33 |  | delete |  |  |
| [api/myaccount/save_signature.php](/code/files/api/myaccount/save_signature.php.md):46 |  | update | `name`, `body`, `updated_datetime` |  |
| [api/myaccount/save_signature.php](/code/files/api/myaccount/save_signature.php.md):60 |  | insert | `analyst_id`, `name`, `body`, `is_default`, `display_order` |  |
| [includes/signatures.php](/code/files/includes/signatures.php.md):140 | `setDefaultSignature` | update | `is_default` |  |
| [includes/signatures.php](/code/files/includes/signatures.php.md):142 | `setDefaultSignature` | update | `is_default` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/myaccount/save_signature.php](/code/files/api/myaccount/save_signature.php.md) | L53, L68 |  |
| [includes/signatures.php](/code/files/includes/signatures.php.md) | signaturesForAnalyst L95 |  |

