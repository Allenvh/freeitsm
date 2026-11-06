# cmdb_class_property_options

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `property_id` | `INT NOT NULL` |  |
| `option_value` | `VARCHAR(255) NOT NULL` |  |
| `colour` | `VARCHAR(7) NULL` |  |
| `display_order` | `INT NULL DEFAULT 0` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md):111 |  | delete |  |  |
| [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md):112 |  | insert | `property_id`, `option_value`, `colour`, `display_order` |  |
| [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md):132 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/cmdb/get_class_properties.php](/code/files/api/cmdb/get_class_properties.php.md) | L42 |  |
| [api/cmdb/get_object.php](/code/files/api/cmdb/get_object.php.md) | L127 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbClassesGet L107 |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md) | CmdbService::propertyOptionValues L601 |  |

