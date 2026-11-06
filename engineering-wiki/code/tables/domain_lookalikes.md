# domain_lookalikes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `domain_id` | `INT NOT NULL` |  |
| `lookalike` | `VARCHAR(253) NOT NULL` |  |
| `technique` | `VARCHAR(30) NOT NULL` |  |
| `has_a` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `has_mx` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `first_seen_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `last_seen_datetime` | `DATETIME NULL` |  |
| `dismissed` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/domains/watch.php](/code/files/api/domains/watch.php.md):38 |  | update | `dismissed` |  |
| [includes/domains/watch.php](/code/files/includes/domains/watch.php.md):204 | `domainLookalikeScan` | insert | `domain_id`, `lookalike`, `technique`, `has_a`, `has_mx`, `first_seen_datetime`, `last_seen_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/domains/get.php](/code/files/api/domains/get.php.md) | L24 |  |
| [includes/domains/read.php](/code/files/includes/domains/read.php.md) | domainListSelect L39 |  |
| [includes/domains/watch.php](/code/files/includes/domains/watch.php.md) | domainLookalikeScan L210 |  |

