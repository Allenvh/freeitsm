# intune_devices

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `intune_id` | `VARCHAR(64) NOT NULL` |  |
| `asset_id` | `INT NULL` |  |
| `device_name` | `VARCHAR(256) NULL` |  |
| `user_principal_name` | `VARCHAR(256) NULL` |  |
| `user_display_name` | `VARCHAR(256) NULL` |  |
| `user_id` | `VARCHAR(64) NULL` |  |
| `operating_system` | `VARCHAR(64) NULL` |  |
| `os_version` | `VARCHAR(64) NULL` |  |
| `compliance_state` | `VARCHAR(32) NULL` |  |
| `management_state` | `VARCHAR(32) NULL` |  |
| `managed_device_owner_type` | `VARCHAR(32) NULL` |  |
| `device_enrollment_type` | `VARCHAR(64) NULL` |  |
| `device_registration_state` | `VARCHAR(32) NULL` |  |
| `enrolled_datetime` | `DATETIME NULL` |  |
| `last_sync_datetime` | `DATETIME NULL` |  |
| `model` | `VARCHAR(128) NULL` |  |
| `manufacturer` | `VARCHAR(128) NULL` |  |
| `serial_number` | `VARCHAR(128) NULL` |  |
| `imei` | `VARCHAR(64) NULL` |  |
| `meid` | `VARCHAR(64) NULL` |  |
| `wifi_mac_address` | `VARCHAR(64) NULL` |  |
| `ethernet_mac_address` | `VARCHAR(64) NULL` |  |
| `azure_ad_device_id` | `VARCHAR(64) NULL` |  |
| `is_encrypted` | `TINYINT(1) NULL` |  |
| `is_supervised` | `TINYINT(1) NULL` |  |
| `jail_broken` | `VARCHAR(16) NULL` |  |
| `total_storage_bytes` | `BIGINT NULL` |  |
| `free_storage_bytes` | `BIGINT NULL` |  |
| `raw_json` | `LONGTEXT NULL` |  |
| `last_seen_local` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/intune.php](/code/files/includes/intune.php.md):183 | `intuneUpsertDevice` | insert | `intune_id`, `device_name`, `user_principal_name`, `user_display_name`, `user_id`, `operating_system`, `os_version`, `compliance_state`, `management_state`, `managed_device_owner_type` |  |
| [includes/intune.php](/code/files/includes/intune.php.md):335 | `intuneLinkDevicesToAssets` | update | `asset_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/intune/app_sync_freshness.php](/code/files/api/intune/app_sync_freshness.php.md) | L31 |  |
| [api/intune/dashboard_data.php](/code/files/api/intune/dashboard_data.php.md) | L25, L28, L36, L42, L50, L70 ... |  |
| [api/intune/dashboard_drilldown.php](/code/files/api/intune/dashboard_drilldown.php.md) | L189, L200, L245 |  |
| [api/intune/get_intune_device.php](/code/files/api/intune/get_intune_device.php.md) | L28 |  |
| [api/intune/list_app_sync_jobs.php](/code/files/api/intune/list_app_sync_jobs.php.md) | L39 |  |
| [includes/intune.php](/code/files/includes/intune.php.md) | intuneLinkDevicesToAssets L315, intuneLinkDevicesToAssets L327, intuneLinkDevicesToAssets L360, intuneSyncDisks L395, intuneGetAppSyncCandidates L631, intuneRunAppSyncJob L834 |  |
| [includes/report_packs/blocks_assets.php](/code/files/includes/report_packs/blocks_assets.php.md) | rpIntuneBreakdown L126, rpIntuneKpis L142 |  |

