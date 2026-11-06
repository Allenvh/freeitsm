# domain_status_incidents

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `domain_id` | `INT NOT NULL` |  |
| `incident_id` | `INT NOT NULL` |  |
| `trigger_kind` | `VARCHAR(30) NOT NULL` |  |
| `fingerprint` | `VARCHAR(100) NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `resolved_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md):201 | `domainStatusRaise` | insert | `domain_id`, `incident_id`, `trigger_kind`, `fingerprint` |  |
| [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md):291 | `domainStatusRun` | update | `resolved_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) | domainStatusReady L48, domainStatusOpenIncident L120, domainStatusRun L261, domainStatusRun L271, domainStatusRun L275 |  |

