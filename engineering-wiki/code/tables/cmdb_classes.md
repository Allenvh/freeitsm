# cmdb_classes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `class_key` | `VARCHAR(100) NOT NULL` |  |
| `name` | `VARCHAR(150) NOT NULL` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `icon_id` | `INT NULL` |  |
| `display_order` | `INT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/cmdb/delete_class.php](/code/files/api/cmdb/delete_class.php.md):41 |  | delete |  |  |
| [api/cmdb/save_class.php](/code/files/api/cmdb/save_class.php.md):72 |  | insert | `class_key`, `name`, `description`, `icon_id`, `display_order`, `is_active`, `created_datetime`, `updated_datetime` |  |
| [api/cmdb/save_class.php](/code/files/api/cmdb/save_class.php.md):88 |  | update | `class_key`, `name`, `description`, `icon_id`, `display_order`, `is_active`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/cmdb/ai_suggest_properties.php](/code/files/api/cmdb/ai_suggest_properties.php.md) | L49 |  |
| [api/cmdb/ai_suggest_questions.php](/code/files/api/cmdb/ai_suggest_questions.php.md) | L33 |  |
| [api/cmdb/delete_class.php](/code/files/api/cmdb/delete_class.php.md) | L39 |  |
| [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md) | L49, L65, L96, L105, L118, L131 |  |
| [api/cmdb/get_class_properties.php](/code/files/api/cmdb/get_class_properties.php.md) | L28 |  |
| [api/cmdb/get_classes.php](/code/files/api/cmdb/get_classes.php.md) | L38 |  |
| [api/cmdb/get_object.php](/code/files/api/cmdb/get_object.php.md) | L59, L66, L83, L96, L112, L187 ... |  |
| [api/cmdb/get_objects.php](/code/files/api/cmdb/get_objects.php.md) | L44, L47 |  |
| [api/cmdb/save_class.php](/code/files/api/cmdb/save_class.php.md) | L63, L81 |  |
| [api/cmdb/search_objects.php](/code/files/api/cmdb/search_objects.php.md) | L50 |  |
| [api/network-mapper/get_diagram.php](/code/files/api/network-mapper/get_diagram.php.md) | L72 |  |
| [api/network-mapper/get_related_objects.php](/code/files/api/network-mapper/get_related_objects.php.md) | L64, L94, L125 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L95 |  |
| [api/tickets/get_ticket_cmdb_objects.php](/code/files/api/tickets/get_ticket_cmdb_objects.php.md) | L39, L46 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbClassesList L62, apiCmdbClassesGet L87, apiCmdbClassesGet L97, apiCmdbLoadObject L154, apiCmdbObjectsList L272, apiCmdbObjectsList L278 ... |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) | apiNmNodeSelect L211, apiNmSuggestionsList L775 |  |
| [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) | cmdbAuditRequiredMissing L91, cmdbAuditBrokenReferences L120, cmdbAuditDependencyBlank L148, cmdbAuditDisconnected L179, cmdbAuditStale L206 |  |
| [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md) | cmdbDirectImpact L133, cmdbDirectImpact L157, cmdbDirectImpact L173, cmdbImpactEdgesFrom L286, cmdbImpactEdgesFrom L297, cmdbImpactEdgesFrom L313 ... |  |
| [includes/domains/links.php](/code/files/includes/domains/links.php.md) | domainLinkDescribe L162 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L227 |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md) | CmdbService::createObject L160, CmdbService::createObject L163, CmdbService::loadObjectRow L481 |  |

