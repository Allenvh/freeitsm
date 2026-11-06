# calendar_categories

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `color` | `VARCHAR(7) NOT NULL DEFAULT '#ef6c00'` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md):230 | `awcEnsureCategory` | insert | `name`, `color`, `is_active` |  |
| [includes/services/calendar.php](/code/files/includes/services/calendar.php.md):134 | `CalendarService::saveCategory` | update | `name`, `color`, `description`, `is_active`, `updated_at` |  |
| [includes/services/calendar.php](/code/files/includes/services/calendar.php.md):141 | `CalendarService::saveCategory` | insert | `name`, `color`, `description`, `is_active` |  |
| [includes/services/calendar.php](/code/files/includes/services/calendar.php.md):165 | `CalendarService::deleteCategory` | delete |  |  |
| [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md):143 | `slcEnsureRenewalCategory` | insert | `name`, `color`, `is_active` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/calendar/feed.php](/code/files/api/calendar/feed.php.md) | L54 |  |
| [api/calendar/get_categories.php](/code/files/api/calendar/get_categories.php.md) | L23 |  |
| [api/calendar/get_event.php](/code/files/api/calendar/get_event.php.md) | L27 |  |
| [api/calendar/get_events.php](/code/files/api/calendar/get_events.php.md) | L34 |  |
| [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md) | apiCalendarEventSelect L71, apiCalendarCategoriesList L234 |  |
| [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md) | awcEnsureCategory L210, awcEnsureCategory L220, awcAdoptExistingCategory L256 |  |
| [includes/services/calendar.php](/code/files/includes/services/calendar.php.md) | CalendarService::deleteCategory L164, CalendarService::collectEventFields L240 |  |
| [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md) | slcEnsureRenewalCategory L136 |  |

