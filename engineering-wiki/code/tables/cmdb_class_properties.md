# cmdb_class_properties

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `class_id` | `INT NOT NULL` |  |
| `property_key` | `VARCHAR(100) NOT NULL` |  |
| `label` | `VARCHAR(150) NOT NULL` |  |
| `property_type` | `VARCHAR(20) NOT NULL` |  |
| `target_class_id` | `INT NULL` |  |
| `is_required` | `TINYINT(1) NULL DEFAULT 0` |  |
| `spreads_impact` | `TINYINT(1) NOT NULL DEFAULT 0` | object_ref only: 1 = a failure of the referenced object affects the object holding this field. Drives the blast-radius walk. |
| `display_order` | `INT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/cmdb/delete_class_property.php](/code/files/api/cmdb/delete_class_property.php.md):36 |  | delete |  |  |
| [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md):84 |  | insert | `class_id`, `property_key`, `label`, `property_type`, `target_class_id`, `is_required`, `spreads_impact`, `display_order`, `created_datetime` |  |
| [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md):99 |  | update | `property_key`, `label`, `property_type`, `target_class_id`, `is_required`, `spreads_impact`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/cmdb/ai_suggest_properties.php](/code/files/api/cmdb/ai_suggest_properties.php.md) | L55 |  |
| [api/cmdb/ai_suggest_questions.php](/code/files/api/cmdb/ai_suggest_questions.php.md) | L39 |  |
| [api/cmdb/delete_class_property.php](/code/files/api/cmdb/delete_class_property.php.md) | L35 |  |
| [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md) | L65, L131 |  |
| [api/cmdb/get_class_properties.php](/code/files/api/cmdb/get_class_properties.php.md) | L28, L42 |  |
| [api/cmdb/get_classes.php](/code/files/api/cmdb/get_classes.php.md) | L33 |  |
| [api/cmdb/get_object.php](/code/files/api/cmdb/get_object.php.md) | L96, L127 |  |
| [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md) | L75, L92 |  |
| [api/network-mapper/get_related_objects.php](/code/files/api/network-mapper/get_related_objects.php.md) | L125 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbClassesGet L97, apiCmdbClassesGet L107, apiCmdbClassDefs L197 |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) | apiNmSuggestionsList L733 |  |
| [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) | cmdbAuditRequiredMissing L91, cmdbAuditBrokenReferences L120, cmdbAuditDependencyBlank L148, cmdbAuditDisconnected L179 |  |
| [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md) | cmdbHasImpactEdgesConfigured L95, cmdbDirectImpact L157, cmdbImpactEdgesFrom L328 |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md) | CmdbService::classDefs L509, CmdbService::normaliseProperties L577 |  |

