# contacts

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `supplier_id` | `INT NULL` |  |
| `first_name` | `VARCHAR(100) NOT NULL` |  |
| `surname` | `VARCHAR(100) NOT NULL` |  |
| `email` | `VARCHAR(255) NULL` |  |
| `mobile` | `VARCHAR(50) NULL` |  |
| `job_title` | `VARCHAR(100) NULL` |  |
| `direct_dial` | `VARCHAR(50) NULL` |  |
| `switchboard` | `VARCHAR(50) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/contracts/delete_contact.php](/code/files/api/contracts/delete_contact.php.md):33 |  | delete |  |  |
| [api/contracts/save_contact.php](/code/files/api/contracts/save_contact.php.md):48 |  | update | `supplier_id`, `first_name`, `surname`, `email`, `mobile`, `job_title`, `direct_dial`, `switchboard`, `is_active` |  |
| [api/contracts/save_contact.php](/code/files/api/contracts/save_contact.php.md):52 |  | insert | `supplier_id`, `first_name`, `surname`, `email`, `mobile`, `job_title`, `direct_dial`, `switchboard`, `is_active` |  |
| [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md):516 | `apiSupplierContactsCreate` | insert | `supplier_id`, `first_name`, `surname`, `email`, `mobile`, `job_title`, `direct_dial`, `switchboard`, `is_active`, `created_datetime` |  |
| [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md):555 | `apiSupplierContactsUpdate` | update | `first_name`, `surname`, `email`, `mobile`, `job_title`, `direct_dial`, `switchboard`, `is_active` |  |
| [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md):569 | `apiSupplierContactsDelete` | delete |  |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md):243 | `ContractsService::deleteSupplier` | update | `supplier_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/contracts/delete_contact.php](/code/files/api/contracts/delete_contact.php.md) | L32 |  |
| [api/contracts/get_contacts.php](/code/files/api/contracts/get_contacts.php.md) | L24 |  |
| [api/contracts/get_dashboard_stats.php](/code/files/api/contracts/get_dashboard_stats.php.md) | L48 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L380 |  |
| [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) | apiSupplierSelect L157, apiSuppliersGet L439, apiSupplierContactsList L493, apiSupplierContactsCreate L524, apiSupplierContactsUpdate L536, apiSupplierContactsUpdate L560 |  |
| [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) | apiDomainSelect L44, apiDomainSelect L48 |  |
| [includes/domains/customer.php](/code/files/includes/domains/customer.php.md) | domainCustomerSupplierSearch L82 |  |
| [includes/domains/read.php](/code/files/includes/domains/read.php.md) | domainListSelect L32, domainListSelect L39, domainApiLookups L211 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleSupplierRows L667, peopleSupplierRows L673, supplierDetail L724, supplierContactDetail L749, peopleContractorTasks L793, peopleSupplierDomains L891 |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) | projectChatTurn L395 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L520 |  |
| [includes/services/domains.php](/code/files/includes/services/domains.php.md) | DomainsService::normaliseParties L850, DomainsService::normaliseParties L865, DomainsService::auditDisplay L1016, DomainsService::auditDisplay L1019 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::contractorMemberSql L117 |  |
| [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) | tasksContractors L63, tasksContractorValidate L103, tasksContractorChoices L123, tasksContractorEmail L152 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolSupplierContact L818 |  |

