# freemail_domains

Admin-added public/free-email domains (gmail.com etc. are built into the code; this table holds extra ones an MSP wants treated as public). These are never mapped to a company — their mail is filed by hand from triage.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `domain` | `VARCHAR(255) NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/add_freemail_domain.php](/code/files/api/system/add_freemail_domain.php.md):62 |  | insert | `domain` |  |
| [api/system/delete_freemail_domain.php](/code/files/api/system/delete_freemail_domain.php.md):28 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md) | L296 |  |
| [api/system/get_freemail_domains.php](/code/files/api/system/get_freemail_domains.php.md) | L25 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | getCustomFreemailDomains L1285 |  |

