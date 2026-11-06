# cmdb_objects

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `class_id` | `INT NOT NULL` |  |
| `name` | `VARCHAR(255) NOT NULL` |  |
| `parent_id` | `INT NULL` |  |
| `is_planned` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `ai_summary` | `LONGTEXT NULL` |  |
| `ai_summary_generated_at` | `DATETIME NULL` |  |
| `tenant_id` | `INT NULL` | Multi-tenancy SCOPED DATA: the company this CI belongs to, NULL = Default's. Only cmdb_objects carries it — classes/properties/relationship types are install-wide config, and the child tables inherit from here. |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md):240 |  | update | `ai_summary`, `ai_summary_generated_at` |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):201 | `CmdbService::createObject` | insert | `class_id`, `name`, `parent_id`, `is_planned`, `tenant_id`, `created_datetime`, `updated_datetime` |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):267 | `CmdbService::updateObject` | update | `name`, `parent_id`, `is_planned`, `updated_datetime` |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):270 | `CmdbService::updateObject` | update | `name`, `parent_id`, `updated_datetime` |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):314 | `CmdbService::deleteObject` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/cmdb/create_impact_diagram.php](/code/files/api/cmdb/create_impact_diagram.php.md) | L75 |  |
| [api/cmdb/delete_class.php](/code/files/api/cmdb/delete_class.php.md) | L32 |  |
| [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md) | L49, L65, L96, L105, L118, L131 |  |
| [api/cmdb/get_classes.php](/code/files/api/cmdb/get_classes.php.md) | L33 |  |
| [api/cmdb/get_object.php](/code/files/api/cmdb/get_object.php.md) | L59, L83, L108, L187, L204 |  |
| [api/cmdb/get_objects.php](/code/files/api/cmdb/get_objects.php.md) | L39, L44 |  |
| [api/cmdb/search_objects.php](/code/files/api/cmdb/search_objects.php.md) | L50 |  |
| [api/network-mapper/get_diagram.php](/code/files/api/network-mapper/get_diagram.php.md) | L72 |  |
| [api/network-mapper/get_related_objects.php](/code/files/api/network-mapper/get_related_objects.php.md) | L44, L64, L94, L125 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3268 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L95 |  |
| [api/tickets/get_ticket_cmdb_objects.php](/code/files/api/tickets/get_ticket_cmdb_objects.php.md) | L39 |  |
| [api/tickets/save_ticket_cmdb_object.php](/code/files/api/tickets/save_ticket_cmdb_object.php.md) | L40, L59 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbClassesList L62, apiCmdbLoadObject L154, apiCmdbObjectsList L272, apiCmdbObjectsList L278, apiCmdbObjectsGet L311, apiCmdbObjectsGet L358 ... |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) | apiNmNodeSelect L211, apiNmConnectorsForDiagram L250, apiNmValidateObjectExists L322, apiNmObjectProperties L605, apiNmSuggestionsList L775 |  |
| [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) | cmdbRunAudit L52, cmdbAuditRequiredMissing L91, cmdbAuditBrokenReferences L120, cmdbAuditDependencyBlank L148, cmdbAuditDisconnected L179, cmdbAuditStale L206 |  |
| [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md) | cmdbImpactTenantScope L111, cmdbDirectImpact L133, cmdbDirectImpact L157, cmdbDirectImpact L173, cmdbImpactEdgesFrom L286, cmdbImpactEdgesFrom L297 ... |  |
| [includes/domains/links.php](/code/files/includes/domains/links.php.md) | domainLinkDescribe L162, domainLinkSearch L274 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L227, projectLinkSearch L358 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L479 |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md) | CmdbService::assertSameCompany L109, CmdbService::tenantOf L424, CmdbService::loadObjectRow L481, CmdbService::validateParent L654, CmdbService::validateParent L666, CmdbService::descendantIds L686 |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md) | NetworkMapperService::validateObjectExists L534 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | analystCanAccessCmdbObject L436 |  |
| [includes/typed_fields.php](/code/files/includes/typed_fields.php.md) | L489, L496 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolImpactOf L607, warbotToolImpactOf L620 |  |

