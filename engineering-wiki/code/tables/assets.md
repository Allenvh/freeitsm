# assets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `hostname` | `VARCHAR(50) NULL` |  |
| `manufacturer` | `VARCHAR(50) NULL` |  |
| `model` | `VARCHAR(50) NULL` |  |
| `memory` | `BIGINT NULL` |  |
| `service_tag` | `VARCHAR(50) NULL` |  |
| `operating_system` | `VARCHAR(50) NULL` |  |
| `feature_release` | `VARCHAR(10) NULL` |  |
| `build_number` | `VARCHAR(50) NULL` |  |
| `cpu_name` | `VARCHAR(250) NULL` |  |
| `speed` | `BIGINT NULL` |  |
| `bios_version` | `VARCHAR(20) NULL` |  |
| `first_seen` | `DATETIME NULL` |  |
| `last_seen` | `DATETIME NULL` |  |
| `asset_type_id` | `INT NULL` |  |
| `asset_status_id` | `INT NULL` |  |
| `location_id` | `INT NULL` |  |
| `domain` | `VARCHAR(100) NULL` |  |
| `logged_in_user` | `VARCHAR(100) NULL` |  |
| `last_boot_utc` | `DATETIME NULL` |  |
| `tpm_version` | `VARCHAR(50) NULL` |  |
| `bitlocker_status` | `VARCHAR(20) NULL` |  |
| `gpu_name` | `VARCHAR(250) NULL` |  |
| `purchase_date` | `DATE NULL` |  |
| `purchase_cost` | `DECIMAL(12,2) NULL` |  |
| `supplier_id` | `INT NULL` |  |
| `cost_centre_id` | `INT NULL` | GH #160: the cost centre it is charged to - see includes/cost_centre_assign.php |
| `order_number` | `VARCHAR(100) NULL` |  |
| `warranty_expiry` | `DATE NULL` |  |
| `lease_start` | `DATE NULL` | when a leased asset's lease began (3.5.0) |
| `lease_expiry` | `DATE NULL` |  |
| `tenant_id` | `INT NULL` | Multi-tenancy: the company this asset belongs to (NULL = Default). |
| `asset_tag` | `VARCHAR(64) NULL` | QR labels (#935). asset_tag is the printed human number, unique per company in application code (a UNIQUE index can't hold it — NULL tenant_id defeats it). qr_token is what the QR encodes. |
| `qr_token` | `VARCHAR(64) NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/delete_asset_status_type.php](/code/files/api/assets/delete_asset_status_type.php.md):53 |  | update | `asset_status_id` |  |
| [api/assets/delete_asset_type.php](/code/files/api/assets/delete_asset_type.php.md):55 |  | update | `asset_type_id` |  |
| [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md):149 |  | update | `last_seen` |  |
| [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md):175 |  | update | `manufacturer`, `model`, `memory`, `service_tag`, `operating_system`, `feature_release`, `build_number`, `cpu_name`, `speed`, `bios_version` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1044 |  | update | `last_seen` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3541 |  | update | `supplier_id` |  |
| [includes/asset_labels.php](/code/files/includes/asset_labels.php.md):83 | `assetEnsureToken` | update | `qr_token` |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):191 | `AssetsService::createAsset` | insert |  |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):286 | `AssetsService::updateFields` | update |  |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):699 | `AssetsService::moveToCompany` | update |  |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):1371 | `AssetsService::updateAssetHostname` | update | `hostname` |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md):1437 | `AssetsService::createDiscoveredAsset` | insert |  |  |
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md):841 | `AssetImportService::handleMissing` | update | `asset_status_id` |  |
| [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md):427 | `AssetTagsService::assign` | update | `asset_tag` |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md):246 | `ContractsService::deleteSupplier` | update | `supplier_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md) | L120 |  |
| [api/assets/delete_asset_status_type.php](/code/files/api/assets/delete_asset_status_type.php.md) | L61 |  |
| [api/assets/delete_asset_type.php](/code/files/api/assets/delete_asset_type.php.md) | L64 |  |
| [api/assets/find_asset.php](/code/files/api/assets/find_asset.php.md) | L52 |  |
| [api/assets/get_asset_custom_fields.php](/code/files/api/assets/get_asset_custom_fields.php.md) | L64 |  |
| [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md) | L136, L153 |  |
| [api/assets/get_widget_data.php](/code/files/api/assets/get_widget_data.php.md) | L61, L68, L75, L112 |  |
| [api/assets/leases/asset_search.php](/code/files/api/assets/leases/asset_search.php.md) | L22 |  |
| [api/assets/resolve_scan.php](/code/files/api/assets/resolve_scan.php.md) | L59 |  |
| [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md) | L100 |  |
| [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md) | L55 |  |
| [api/assets/set_asset_status_type_hidden.php](/code/files/api/assets/set_asset_status_type_hidden.php.md) | L56 |  |
| [api/assets/set_asset_type_hidden.php](/code/files/api/assets/set_asset_type_hidden.php.md) | L65 |  |
| [api/external/device-manager/submit/index.php](/code/files/api/external/device-manager/submit/index.php.md) | L104 |  |
| [api/intune/app_sync_freshness.php](/code/files/api/intune/app_sync_freshness.php.md) | L31 |  |
| [api/intune/list_app_sync_jobs.php](/code/files/api/intune/list_app_sync_jobs.php.md) | L39 |  |
| [api/self-service/get_my_assets.php](/code/files/api/self-service/get_my_assets.php.md) | L37 |  |
| [api/software/get_app_machines.php](/code/files/api/software/get_app_machines.php.md) | L28 |  |
| [api/software/get_software_drilldown.php](/code/files/api/software/get_software_drilldown.php.md) | L46, L59 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3529 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L152, L167 |  |
| [api/tickets/get_ticket_assets.php](/code/files/api/tickets/get_ticket_assets.php.md) | L39 |  |
| [api/tickets/save_ticket_asset.php](/code/files/api/tickets/save_ticket_asset.php.md) | L47, L63 |  |
| [api/tickets/search_linkable_assets.php](/code/files/api/tickets/search_linkable_assets.php.md) | L49 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetSelect L52, apiAssetsList L323 |  |
| [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) | apiSoftwareAppMachinesList L202 |  |
| [asset-management/labels.php](/code/files/asset-management/labels.php.md) | L73, L104 |  |
| [asset-management/scan.php](/code/files/asset-management/scan.php.md) | L65 |  |
| [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md) | assetDiskMatchCount L110 |  |
| [includes/asset_labels.php](/code/files/includes/asset_labels.php.md) | assetLabelsSchemaReady L54, assetEnsureToken L72, assetIdForToken L113, assetTagAvailable L139 |  |
| [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md) | awcSyncKind L151 |  |
| [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) | contractAssetCanReach L50, contractAssetSelect L64, contractAssetLink L166, contractAssetSearch L263 |  |
| [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md) | costCentreUsage L162 |  |
| [includes/db_verify_preview.php](/code/files/includes/db_verify_preview.php.md) | dbPreviewRepairs L148 |  |
| [includes/intune.php](/code/files/includes/intune.php.md) | intuneLinkDevicesToAssets L315, intuneGetAppSyncCandidates L631, intuneRunAppSyncJob L834 |  |
| [includes/leases.php](/code/files/includes/leases.php.md) | leaseGet L419, leaseAssetsAdd L563, leaseAssetDatesEdited L726, leaseDueBack L778, leaseSyncCalendar L864 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleCompanyCards L178, peopleAssets L376, peopleAssets L381, peopleSupplierAssets L850 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L189, projectLinkSearch L346 |  |
| [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) | projectBuiltinTemplates L107 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L470 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewAsset L306, recordPreviewAsset L338 |  |
| [includes/report_packs/blocks_assets.php](/code/files/includes/report_packs/blocks_assets.php.md) | rpAssetsBreakdown L43, rpAssetsKpis L62, rpAssetsExpiring L90 |  |
| [includes/report_packs/blocks_software.php](/code/files/includes/report_packs/blocks_software.php.md) | rpSoftwareScope L25 |  |
| [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) | AssetFieldsService::assertUnique L364, AssetFieldsService::loadAsset L907, AssetFieldsService::tenantOfAsset L935 |  |
| [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md) | AssetImportService::availableMatchKeys L92, AssetImportService::findMatch L595, AssetImportService::loadCore L1063 |  |
| [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md) | AssetTagsService::clashMessage L343, AssetTagsService::assign L413 |  |
| [includes/services/assets.php](/code/files/includes/services/assets.php.md) | AssetsService::createAsset L133, AssetsService::updateFields L248, AssetsService::dispatch L565, AssetsService::moveToCompany L636, AssetsService::moveToCompany L643, AssetsService::loadRow L722 ... |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) | ContractsService::assertMovable L347 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | analystCanAccessAsset L560 |  |
| [includes/typed_fields.php](/code/files/includes/typed_fields.php.md) | L537, L545 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolAssetLookup L569 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L713, getWatchtowerData L716, getWatchtowerData L735, getWatchtowerData L764 |  |
| [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) | workflowEmitWarrantyExpiries L197 |  |
| [self-service/includes/header.php](/code/files/self-service/includes/header.php.md) | L137 |  |

