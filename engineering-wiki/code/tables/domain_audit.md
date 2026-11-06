# domain_audit

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `domain_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `field_name` | `VARCHAR(100) NOT NULL` |  |
| `old_value` | `VARCHAR(1000) NULL` |  |
| `new_value` | `VARCHAR(1000) NULL` |  |
| `source` | `VARCHAR(20) NOT NULL DEFAULT 'app'` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/domains/watch.php](/code/files/api/domains/watch.php.md):25 |  | delete |  |  |
| [api/domains/watch.php](/code/files/api/domains/watch.php.md):34 |  | delete |  |  |
| [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md):152 | `domainWatchMark` | delete |  |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):966 | `DomainsService::audit` | insert | `domain_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `source`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/domains/get.php](/code/files/api/domains/get.php.md) | L17, L32 |  |
| [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) | apiDomainsHistory L390, apiDomainsHistory L393 |  |
| [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) | domainWatchDue L130 |  |

