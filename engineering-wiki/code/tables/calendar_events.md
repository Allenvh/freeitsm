# calendar_events

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `description` | `LONGTEXT NULL` |  |
| `category_id` | `INT NULL` |  |
| `start_datetime` | `DATETIME NOT NULL` |  |
| `end_datetime` | `DATETIME NULL` |  |
| `all_day` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `location` | `VARCHAR(255) NULL` |  |
| `contract_id` | `INT NULL` |  |
| `created_by` | `INT NOT NULL` |  |
| `source` | `VARCHAR(30) NULL` |  |
| `created_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md):132 | `awcSyncKind` | delete |  |  |
| [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md):161 | `awcSyncKind` | insert | `title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source` |  |
| [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md):82 | `domainSyncCalendarKind` | delete |  |  |
| [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md):94 | `domainSyncCalendarKind` | insert | `title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source` |  |
| [includes/leases.php](/code/files/includes/leases.php.md):845 | `leaseSyncCalendar` | delete |  |  |
| [includes/leases.php](/code/files/includes/leases.php.md):858 | `leaseSyncCalendar` | insert | `title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source` |  |
| [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md):108 | `projectSyncCalendarKind` | delete |  |  |
| [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md):113 | `projectSyncCalendarKind` | insert | `title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source` |  |
| [includes/services/calendar.php](/code/files/includes/services/calendar.php.md):61 | `CalendarService::saveEvent` | update | `title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `location`, `contract_id`, `updated_at` |  |
| [includes/services/calendar.php](/code/files/includes/services/calendar.php.md):82 | `CalendarService::saveEvent` | insert | `title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `location`, `contract_id`, `created_by` |  |
| [includes/services/calendar.php](/code/files/includes/services/calendar.php.md):104 | `CalendarService::deleteEvent` | delete |  |  |
| [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md):60 | `syncSoftwareLicenceCalendar` | delete |  |  |
| [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md):84 | `syncSoftwareLicenceCalendar` | insert | `title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/calendar/feed.php](/code/files/api/calendar/feed.php.md) | L54 |  |
| [api/calendar/get_event.php](/code/files/api/calendar/get_event.php.md) | L27 |  |
| [api/calendar/get_events.php](/code/files/api/calendar/get_events.php.md) | L34 |  |
| [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md) | apiCalendarEventSelect L71, apiCalendarEventsList L180, apiCalendarCategoriesList L234 |  |
| [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md) | awcAdoptExistingCategory L262 |  |
| [includes/services/calendar.php](/code/files/includes/services/calendar.php.md) | CalendarService::deleteCategory L158, CalendarService::loadEventRow L185 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L399, getWatchtowerData L409 |  |

