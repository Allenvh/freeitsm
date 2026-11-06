# domain_statuses

Domains module (GitHub #154) — see the matching block in freeitsm.sql for what each table is for. FKs live in api/system/db_verify.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `colour` | `VARCHAR(7) NULL` |  |
| `alerts_enabled` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):832 |  | insert | `name`, `colour`, `alerts_enabled`, `display_order` |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):558 | `DomainsService::saveStatus` | update | `name`, `colour`, `alerts_enabled`, `is_active`, `display_order` |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):562 | `DomainsService::saveStatus` | insert | `name`, `colour`, `alerts_enabled`, `is_active`, `display_order`, `created_datetime` |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):579 | `DomainsService::deleteStatus` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md) | domainImportResolveNames L60 |  |
| [api/domains/statuses.php](/code/files/api/domains/statuses.php.md) | L13 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L830 |  |
| [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) | apiDomainSelect L48, apiDomainStatusesList L409 |  |
| [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) | domainAlertsDue L104, domainRenewalActionsRun L411 |  |
| [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md) | domainSyncCalendarKind L87 |  |
| [includes/domains/links.php](/code/files/includes/domains/links.php.md) | domainLinkDomainRows L365 |  |
| [includes/domains/read.php](/code/files/includes/domains/read.php.md) | domainListSelect L39, domainApiLookups L198 |  |
| [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) | domainStatusRow L92 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleDomains L457, peopleSupplierDomains L903 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewDomain L407 |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md) | DomainsService::createDomain L157, DomainsService::saveStatus L553, DomainsService::auditDisplay L1012 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L610, getWatchtowerData L644 |  |

