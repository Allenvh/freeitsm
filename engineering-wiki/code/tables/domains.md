# domains

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NULL` |  |
| `domain_name` | `VARCHAR(253) NOT NULL` |  |
| `display_name` | `VARCHAR(253) NULL` |  |
| `status_id` | `INT NULL` |  |
| `purpose` | `VARCHAR(20) NOT NULL DEFAULT 'primary'` |  |
| `registrar_supplier_id` | `INT NULL` |  |
| `registrar_account_id` | `INT NULL` |  |
| `registrar_name` | `VARCHAR(255) NULL` |  |
| `registration_date` | `DATE NULL` |  |
| `expiry_date` | `DATE NULL` |  |
| `last_renewed_date` | `DATE NULL` |  |
| `registry_updated_date` | `DATE NULL` |  |
| `renewal_mode` | `VARCHAR(20) NOT NULL DEFAULT 'unknown'` |  |
| `transfer_lock` | `TINYINT(1) NULL` |  |
| `registry_lock` | `TINYINT(1) NULL` |  |
| `dnssec` | `TINYINT(1) NULL` |  |
| `registry_statuses` | `VARCHAR(500) NULL` |  |
| `auth_code` | `TEXT NULL` |  |
| `registrant_name` | `VARCHAR(255) NULL` |  |
| `owner_analyst_id` | `INT NULL` |  |
| `tech_contact_id` | `INT NULL` |  |
| `customer_user_id` | `INT NULL` | The person at the customer this domain is for (#153/#154). The domain's own company (tenant_id) stays its company - there is deliberately no second "customer company" column beside it. |
| `customer_supplier_id` | `INT NULL` | #162: the customer may instead be a supplier (an organisation), optionally one of its contacts; and the technical contact may be one of your own analysts rather than a supplier contact. One of each kind is set, never two. |
| `customer_contact_id` | `INT NULL` |  |
| `tech_analyst_id` | `INT NULL` |  |
| `nameservers` | `TEXT NULL` |  |
| `dns_provider` | `VARCHAR(255) NULL` |  |
| `hosting_provider` | `VARCHAR(255) NULL` |  |
| `ssl_hosts` | `VARCHAR(500) NULL` |  |
| `dkim_selectors` | `VARCHAR(255) NULL` |  |
| `cost` | `DECIMAL(10,2) NULL` |  |
| `currency` | `VARCHAR(3) NULL` |  |
| `billing_years` | `TINYINT NOT NULL DEFAULT 1` |  |
| `cost_centre` | `VARCHAR(100) NULL` |  |
| `contract_id` | `INT NULL` |  |
| `tags` | `VARCHAR(500) NULL` |  |
| `notes` | `TEXT NULL` |  |
| `monitoring_enabled` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `lookup_source` | `VARCHAR(10) NULL` |  |
| `last_lookup_datetime` | `DATETIME NULL` |  |
| `last_lookup_error` | `VARCHAR(255) NULL` |  |
| `last_check_datetime` | `DATETIME NULL` |  |
| `ssl_expiry_date` | `DATE NULL` |  |
| `ssl_issuer` | `VARCHAR(255) NULL` |  |
| `security_score` | `TINYINT NULL` |  |
| `security_grade` | `VARCHAR(2) NULL` |  |
| `check_results` | `LONGTEXT NULL` |  |
| `watch_baseline` | `LONGTEXT NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):161 | `DomainsService::createDomain` | insert |  |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):166 | `DomainsService::createDomain` | update | `auth_code` |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):230 | `DomainsService::updateDomain` | update |  |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):261 | `DomainsService::deleteDomain` | delete |  |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):421 | `DomainsService::applyLookup` | update |  |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):438 | `DomainsService::recordLookupError` | update | `last_lookup_datetime`, `last_lookup_error` |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):464 | `DomainsService::runChecks` | update | `check_results`, `security_score`, `security_grade`, `ssl_expiry_date`, `ssl_issuer`, `watch_baseline`, `last_check_datetime` |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md):630 | `DomainsService::deleteRegistrarAccount` | update | `registrar_account_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/domains/accounts.php](/code/files/api/domains/accounts.php.md) | L20 |  |
| [api/domains/dashboard.php](/code/files/api/domains/dashboard.php.md) | L27 |  |
| [api/domains/links.php](/code/files/api/domains/links.php.md) | L63 |  |
| [api/domains/people.php](/code/files/api/domains/people.php.md) | L16 |  |
| [api/domains/settings.php](/code/files/api/domains/settings.php.md) | L45 |  |
| [api/domains/statuses.php](/code/files/api/domains/statuses.php.md) | L13 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L401 |  |
| [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) | apiDomainSelect L48, apiDomainsList L241, apiDomainAccountsList L423 |  |
| [domains/view.php](/code/files/domains/view.php.md) | L26 |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) | analystReassignLive L539 |  |
| [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) | domainAlertsDue L104, domainRenewalActionsRun L411 |  |
| [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md) | domainSyncCalendarKind L87 |  |
| [includes/domains/customer.php](/code/files/includes/domains/customer.php.md) | domainCustomerReady L28, domainPartiesReady L38 |  |
| [includes/domains/links.php](/code/files/includes/domains/links.php.md) | domainLinkPickDomains L340, domainLinkDomainRows L365, domainsForContract L395 |  |
| [includes/domains/read.php](/code/files/includes/domains/read.php.md) | domainListSelect L39, domainDetail L160 |  |
| [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) | domainScheduledRun L50, domainScheduledRun L71, domainWatchDue L130 |  |
| [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) | domainStatusRow L92 |  |
| [includes/feature_bingo/cards/domains.php](/code/files/includes/feature_bingo/cards/domains.php.md) | L34, L112, L124 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleDomains L457, peopleSupplierDomains L903 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L484 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewDomain L407 |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md) | DomainsService::createDomain L137, DomainsService::updateDomain L198, DomainsService::deleteStatus L574, DomainsService::loadRow L645 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | analystCanAccessDomain L530 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L610, getWatchtowerData L644 |  |

