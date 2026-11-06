# cmdb_icons

CMDB ----------------------------------------------------------

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `icon_key` | `VARCHAR(50) NOT NULL` |  |
| `label` | `VARCHAR(100) NOT NULL` |  |
| `display_order` | `INT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NULL DEFAULT 1` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3813 |  | insert | `icon_key`, `label`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_types.php](/code/files/api/assets/get_asset_types.php.md) | L58 |  |
| [api/cmdb/get_classes.php](/code/files/api/cmdb/get_classes.php.md) | L38 |  |
| [api/network-mapper/get_diagram.php](/code/files/api/network-mapper/get_diagram.php.md) | L72 |  |
| [api/network-mapper/get_related_objects.php](/code/files/api/network-mapper/get_related_objects.php.md) | L64, L94, L125 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3812, L3815 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbClassesList L62, apiCmdbClassesGet L87 |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) | apiNmNodeSelect L211, apiNmValidateIcon L369, apiNmSuggestionsList L775, apiCmdbIconsList L1012 |  |
| [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md) | L45 |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md) | NetworkMapperService::validateIcon L586 |  |

