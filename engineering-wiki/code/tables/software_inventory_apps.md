# software_inventory_apps

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `display_name` | `VARCHAR(512) NOT NULL` |  |
| `publisher` | `VARCHAR(512) NULL` |  |
| `first_detected` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `source` | `VARCHAR(20) NOT NULL DEFAULT 'agent'` | Manually added applications (#1549). 'agent' = discovered by the inventory agent / system-info submit / Intune; 'manual' = typed in. Cloud platforms have nothing to install, so nothing discovers them — and that also made all of `software_licences` unreachable for SaaS, since its app_id is NOT NULL. ⚠️ The agent MAY adopt a manual row (its installs should attach to the row somebody already curated) but must NEVER overwrite the name, publisher, URL or notes a person chose, nor flip source back. |
| `app_url` | `VARCHAR(500) NULL` |  |
| `notes` | `LONGTEXT NULL` |  |
| `created_by` | `INT NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md):287 |  | update | `display_name`, `publisher` |  |
| [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md):294 |  | insert | `display_name`, `publisher` |  |
| [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md):481 |  | insert | `display_name`, `publisher` |  |
| [api/software/delete_app.php](/code/files/api/software/delete_app.php.md):65 |  | delete |  |  |
| [api/software/save_app.php](/code/files/api/software/save_app.php.md):94 |  | update | `display_name`, `publisher`, `app_url`, `notes` |  |
| [api/software/save_app.php](/code/files/api/software/save_app.php.md):99 |  | insert | `display_name`, `publisher`, `app_url`, `notes`, `source`, `created_by`, `first_detected` |  |
| [includes/intune.php](/code/files/includes/intune.php.md):747 | `intuneUpsertSoftwareForAsset` | insert | `display_name`, `publisher` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_software.php](/code/files/api/assets/get_asset_software.php.md) | L34 |  |
| [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md) | L234, L298 |  |
| [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md) | L471 |  |
| [api/software/delete_app.php](/code/files/api/software/delete_app.php.md) | L38 |  |
| [api/software/get_apps.php](/code/files/api/software/get_apps.php.md) | L46 |  |
| [api/software/get_licences.php](/code/files/api/software/get_licences.php.md) | L21 |  |
| [api/software/get_software_dashboard.php](/code/files/api/software/get_software_dashboard.php.md) | L22 |  |
| [api/software/get_software_drilldown.php](/code/files/api/software/get_software_drilldown.php.md) | L33 |  |
| [api/software/get_software_widget_data.php](/code/files/api/software/get_software_widget_data.php.md) | L29, L68, L87 |  |
| [api/software/get_software_widget_library.php](/code/files/api/software/get_software_widget_library.php.md) | L20 |  |
| [api/software/save_app.php](/code/files/api/software/save_app.php.md) | L70, L85 |  |
| [api/software/save_software_dashboard_widget.php](/code/files/api/software/save_software_dashboard_widget.php.md) | L75 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetSoftwareList L619 |  |
| [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) | apiSoftwareAppsList L78, apiSoftwareAppsList L88, apiSoftwareLoadApp L124, apiSoftwareLicenceSelect L229, apiSoftwareLicencesList L349 |  |
| [includes/documents.php](/code/files/includes/documents.php.md) | documentEntityRegistry L295 |  |
| [includes/intune.php](/code/files/includes/intune.php.md) | intuneUpsertSoftwareForAsset L743 |  |
| [includes/report_packs/blocks_software.php](/code/files/includes/report_packs/blocks_software.php.md) | rpSoftwareByPublisher L38, rpSoftwareTopApps L59, rpSoftwareLicences L114 |  |
| [includes/services/software.php](/code/files/includes/services/software.php.md) | SoftwareService::collectFields L160 |  |
| [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md) | syncSoftwareLicenceCalendar L72 |  |

