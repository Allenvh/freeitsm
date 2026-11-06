# software_licences

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `app_id` | `INT NOT NULL` |  |
| `licence_type` | `VARCHAR(50) NOT NULL` |  |
| `licence_key` | `VARCHAR(500) NULL` |  |
| `quantity` | `INT NULL` |  |
| `renewal_date` | `DATE NULL` |  |
| `notice_period_days` | `INT NULL` |  |
| `portal_url` | `VARCHAR(500) NULL` |  |
| `cost` | `DECIMAL(10,2) NULL` |  |
| `currency` | `VARCHAR(10) NULL DEFAULT 'GBP'` |  |
| `purchase_date` | `DATE NULL` |  |
| `vendor_contact` | `VARCHAR(500) NULL` |  |
| `notes` | `LONGTEXT NULL` |  |
| `status` | `VARCHAR(20) NOT NULL DEFAULT 'Active'` |  |
| `created_by` | `INT NULL` |  |
| `created_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/software.php](/code/files/includes/services/software.php.md):51 | `SoftwareService::saveLicence` | update | `app_id`, `licence_type`, `licence_key`, `quantity`, `renewal_date`, `notice_period_days`, `portal_url`, `cost`, `currency`, `purchase_date` |  |
| [includes/services/software.php](/code/files/includes/services/software.php.md):67 | `SoftwareService::saveLicence` | insert | `app_id`, `licence_type`, `licence_key`, `quantity`, `renewal_date`, `notice_period_days`, `portal_url`, `cost`, `currency`, `purchase_date` |  |
| [includes/services/software.php](/code/files/includes/services/software.php.md):91 | `SoftwareService::deleteLicence` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/software/delete_app.php](/code/files/api/software/delete_app.php.md) | L47 |  |
| [api/software/get_apps.php](/code/files/api/software/get_apps.php.md) | L46 |  |
| [api/software/get_licences.php](/code/files/api/software/get_licences.php.md) | L21 |  |
| [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) | apiSoftwareAppsList L88, apiSoftwareAppsGet L153, apiSoftwareLicenceSelect L229, apiSoftwareLicencesList L349 |  |
| [includes/report_packs/blocks_software.php](/code/files/includes/report_packs/blocks_software.php.md) | rpSoftwareKpis L91, rpSoftwareLicences L114 |  |
| [includes/services/software.php](/code/files/includes/services/software.php.md) | SoftwareService::loadLicenceRow L132 |  |
| [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md) | syncSoftwareLicenceCalendar L72 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L537, getWatchtowerData L541, getWatchtowerData L547, getWatchtowerData L559 |  |

