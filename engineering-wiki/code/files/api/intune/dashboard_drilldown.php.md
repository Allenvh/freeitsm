# api/intune/dashboard_drilldown.php

> API endpoint - 273 lines

API: Intune Dashboard Drill-down

Returns the list of intune_devices matching a chart-click filter,
paginated for the modal table or returned as CSV for export.

GET parameters:
  filter      Required. One of:
                - compliance       value = compliance_state (e.g. "compliant")
                - os               value = operating_system  (e.g. "Windows")
                - owner            value = managed_device_owner_type (e.g. "company")
                - manufacturer     value = manufacturer (e.g. "Apple")
                - os_version       value = "<os>||<version>"
                - last_sync        value = bucket: today | week | month | quarter | old | never
                - enrolment_day    value = YYYY-MM-DD
                - encryption_os    value = "0||<os>" or "1||<os>"
                - kpi_stale        value ignored — devices not synced in 30+ days (or never)
                - kpi_recent       value ignored — devices enrolled in last 30 days
                - kpi_compliant    value ignored — compliance_state = 'compliant'
                - kpi_encrypted    value ignored — is_encrypted = 1
  value       Filter value (see above).
  page        1-based page index. Default 1.
  page_size   Rows per page. Default 25, capped at 100.
  format      "json" (default) or "csv". CSV ignores pagination and returns
              all matching rows as a downloadable attachment.

## The endpoint

| | |
|---|---|
| URL | `api/intune/dashboard_drilldown.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |
| Reads | `GET filter`, `GET value`, `GET page`, `GET page_size`, `GET format` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [buildFilter()](/code/functions/buildFilter.md) | 55 | Resolve a filter type + value into a SQL WHERE fragment + bound params. All filter types are validated against this whitelist; nothing user-supplied goes into the SQL string directly. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 189 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 200 |  | read [intune_devices](/code/tables/intune_devices.md) |  |
| 245 |  | read [intune_devices](/code/tables/intune_devices.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

