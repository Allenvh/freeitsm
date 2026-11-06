# calendar_connections

── Scheduled work -> the analyst's own calendar (GH #75) ──────────────── Admin-level connection, per-analyst enrolment, and the map of what we put where. See database/freeitsm.sql for why the split is three tables. ⚠️ `$schema` carries COLUMNS ONLY — the foreign keys and unique keys live in freeitsm.sql; db_verify creates and adds columns, nothing else.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `provider` | `VARCHAR(20) NOT NULL DEFAULT 'microsoft'` |  |
| `credentials` | `LONGTEXT NULL` |  |
| `mailbox_id` | `INT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `token_data` | `LONGTEXT NULL` |  |
| `last_error` | `VARCHAR(500) NULL` |  |
| `last_error_datetime` | `DATETIME NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md):345 |  | update | `name`, `provider`, `mailbox_id`, `credentials`, `is_active`, `last_error`, `last_error_datetime`, `token_data`, `updated_datetime` |  |
| [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md):353 |  | insert | `name`, `provider`, `mailbox_id`, `credentials`, `created_by` |  |
| [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md):372 |  | update | `last_error`, `last_error_datetime` |  |
| [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md):377 |  | update | `last_error`, `last_error_datetime` |  |
| [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md):526 |  | delete |  |  |
| [includes/calendar_sync/MicrosoftCalendarProvider.php](/code/files/includes/calendar_sync/MicrosoftCalendarProvider.php.md):105 | `MicrosoftCalendarProvider::token` | update | `token_data` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md) | L150, L212, L260, L512 |  |
| [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) | calendarSyncLoadConnection L148, calendarSyncConnectionIds L187, calendarSyncConnectionProvider L233 |  |

