# tenants

Multi-tenancy foundation — a single install can host multiple client companies (tenants). Invisible until a second tenant exists.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(150) NOT NULL` |  |
| `slug` | `VARCHAR(100) NULL` |  |
| `ticket_code` | `VARCHAR(12) NULL` | The short code that stands in for this company in a ticket number ({COMPANY}). NULL means "derive one from the name" — see TicketNumbering::companyCode(). |
| `logo_path` | `VARCHAR(255) NULL` | The company's own logo (System -> Companies), shown on the Files desktop. NULL falls back to the organisation logo. |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):663 |  | insert | `name`, `is_default`, `is_active`, `created_datetime` |  |
| [api/system/save_tenant.php](/code/files/api/system/save_tenant.php.md):84 |  | update | `name`, `is_active`, `ticket_code` |  |
| [api/system/save_tenant.php](/code/files/api/system/save_tenant.php.md):91 |  | insert | `name`, `is_default`, `is_active`, `ticket_code`, `created_datetime` |  |
| [api/system/tenant_logo.php](/code/files/api/system/tenant_logo.php.md):51 |  | update | `logo_path` |  |
| [api/system/tenant_logo.php](/code/files/api/system/tenant_logo.php.md):62 |  | update | `logo_path` |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md):65 |  | insert | `name`, `is_default`, `is_active`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/contracts/customer_lookup.php](/code/files/api/contracts/customer_lookup.php.md) | L45 |  |
| [api/domains/accounts.php](/code/files/api/domains/accounts.php.md) | L20 |  |
| [api/integrations/get_mapping.php](/code/files/api/integrations/get_mapping.php.md) | L57, L66 |  |
| [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md) | L117 |  |
| [api/rfp-builder/get_rfps.php](/code/files/api/rfp-builder/get_rfps.php.md) | L32 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L661, L1672, L1686, L1712, L1736 |  |
| [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md) | L286, L303, L415 |  |
| [api/system/get_orphaned_tickets.php](/code/files/api/system/get_orphaned_tickets.php.md) | L27 |  |
| [api/system/get_sso_providers.php](/code/files/api/system/get_sso_providers.php.md) | L45 |  |
| [api/system/get_tenant_email_routing.php](/code/files/api/system/get_tenant_email_routing.php.md) | L50 |  |
| [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md) | L41, L53 |  |
| [api/system/get_topology.php](/code/files/api/system/get_topology.php.md) | L43 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L351 |  |
| [api/system/managers.php](/code/files/api/system/managers.php.md) | L113 |  |
| [api/system/save_tenant.php](/code/files/api/system/save_tenant.php.md) | L60 |  |
| [api/system/tenant_logo.php](/code/files/api/system/tenant_logo.php.md) | L36 |  |
| [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md) | L56 |  |
| [api/tickets/get_sendable_mailboxes.php](/code/files/api/tickets/get_sendable_mailboxes.php.md) | L34 |  |
| [api/tickets/get_users.php](/code/files/api/tickets/get_users.php.md) | L59 |  |
| [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) | maManager L58 |  |
| [api/tickets/save_mailbox.php](/code/files/api/tickets/save_mailbox.php.md) | L120 |  |
| [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) | apiEffectiveCompanyScope L191, apiKeyCheckCompanies L211, apiKeyCheckCompanies L219 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetSelect L52 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangeSelect L57 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbLoadObject L154, apiCmdbObjectsList L278 |  |
| [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) | apiCostCentreSelect L33 |  |
| [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) | apiDomainSelect L48, apiDomainAccountsList L423 |  |
| [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) | apiKnowledgeSelect L51 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemSelect L41 |  |
| [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) | apiProjectSelect L45 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketSelect L38 |  |
| [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md) | L91 |  |
| [asset-management/labels.php](/code/files/asset-management/labels.php.md) | L73, L104 |  |
| [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md) | L55 |  |
| [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) | cardDavCreateContact L191 |  |
| [includes/contract_party.php](/code/files/includes/contract_party.php.md) | contractOwnerSql L165, contractPartySql L187, contractNormaliseParty L245 |  |
| [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) | domainAlertsDue L104 |  |
| [includes/domains/read.php](/code/files/includes/domains/read.php.md) | domainListSelect L39 |  |
| [includes/files/storage.php](/code/files/includes/files/storage.php.md) | filesDesktopLogo L187 |  |
| [includes/leases.php](/code/files/includes/leases.php.md) | leaseList L339, leaseDueBack L778 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleListRows L106, peopleCompanies L131, peopleCompanyCards L186, personDetail L219 |  |
| [includes/projects/read.php](/code/files/includes/projects/read.php.md) | projectListRows L328, projectDetail L376 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L508 |  |
| [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md) | CostCentresService::load L133 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::loadTicket L1100 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | tenancyTablesReady L84, tenantCount L111, getDefaultTenantId L140, getDefaultTenantId L142, getAllTenants L156, getTenantById L173 ... |  |
| [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md) | TicketNumbering::resolveTenant L302, TicketNumbering::companyCode L330, TicketNumbering::companyCode L337, TicketNumbering::codeClashes L395 |  |
| [system/sso/index.php](/code/files/system/sso/index.php.md) | L29 |  |
| [system/sso/provider.php](/code/files/system/sso/provider.php.md) | L61 |  |

