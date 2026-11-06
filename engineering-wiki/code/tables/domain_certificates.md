# domain_certificates

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `domain_id` | `INT NOT NULL` |  |
| `crtsh_id` | `BIGINT NOT NULL` |  |
| `common_name` | `VARCHAR(255) NULL` |  |
| `name_value` | `TEXT NULL` |  |
| `issuer` | `VARCHAR(500) NULL` |  |
| `not_before` | `DATETIME NULL` |  |
| `not_after` | `DATETIME NULL` |  |
| `first_seen_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `acknowledged` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/domains/watch.php](/code/files/api/domains/watch.php.md):29 |  | update | `acknowledged` |  |
| [includes/domains/watch.php](/code/files/includes/domains/watch.php.md):70 | `domainCtScan` | insert | `domain_id`, `crtsh_id`, `common_name`, `name_value`, `issuer`, `not_before`, `not_after`, `first_seen_datetime`, `acknowledged` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/domains/get.php](/code/files/api/domains/get.php.md) | L28 |  |
| [includes/domains/read.php](/code/files/includes/domains/read.php.md) | domainListSelect L39 |  |
| [includes/domains/watch.php](/code/files/includes/domains/watch.php.md) | domainCtScan L65 |  |

