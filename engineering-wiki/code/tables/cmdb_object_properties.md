# cmdb_object_properties

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `object_id` | `INT NOT NULL` |  |
| `property_id` | `INT NOT NULL` |  |
| `value_text` | `TEXT NULL` |  |
| `value_number` | `DECIMAL(20,4) NULL` |  |
| `value_date` | `DATETIME NULL` |  |
| `value_boolean` | `TINYINT(1) NULL` |  |
| `value_object_id` | `INT NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):301 | `CmdbService::deleteObject` | update | `value_object_id` |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):302 | `CmdbService::deleteObject` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/cmdb/delete_class_property.php](/code/files/api/cmdb/delete_class_property.php.md) | L28 |  |
| [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md) | L65, L131 |  |
| [api/cmdb/get_object.php](/code/files/api/cmdb/get_object.php.md) | L108 |  |
| [api/network-mapper/get_related_objects.php](/code/files/api/network-mapper/get_related_objects.php.md) | L125 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbObjectsGet L311 |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) | apiNmObjectProperties L605, apiNmSuggestionsList L733 |  |
| [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) | cmdbAuditRequiredMissing L91, cmdbAuditBrokenReferences L120, cmdbAuditDependencyBlank L148, cmdbAuditDisconnected L179 |  |
| [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md) | cmdbDirectImpact L157, cmdbImpactEdgesFrom L328 |  |

