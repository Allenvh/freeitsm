# user_preferences

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `preference_key` | `VARCHAR(100) NOT NULL` |  |
| `preference_value` | `TEXT NULL` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/calendar/get_feed_url.php](/code/files/api/calendar/get_feed_url.php.md):45 |  | insert | `analyst_id`, `preference_key`, `preference_value`, `updated_datetime` |  |
| [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md):41 |  | insert | `analyst_id`, `preference_key`, `preference_value`, `updated_datetime` |  |
| [api/table-views/use.php](/code/files/api/table-views/use.php.md):51 |  | delete |  |  |
| [api/table-views/use.php](/code/files/api/table-views/use.php.md):60 |  | insert | `analyst_id`, `preference_key`, `preference_value`, `updated_datetime` |  |
| [api/tickets/get_schedule_feed_url.php](/code/files/api/tickets/get_schedule_feed_url.php.md):52 |  | delete |  |  |
| [api/tickets/get_schedule_feed_url.php](/code/files/api/tickets/get_schedule_feed_url.php.md):73 |  | insert | `analyst_id`, `preference_key`, `preference_value`, `updated_datetime` |  |
| [api/tickets/save_inbox_display.php](/code/files/api/tickets/save_inbox_display.php.md):44 |  | delete |  |  |
| [api/tickets/save_inbox_display.php](/code/files/api/tickets/save_inbox_display.php.md):81 |  | insert | `analyst_id`, `preference_key`, `preference_value`, `updated_datetime` |  |
| [checklists/settings/index.php](/code/files/checklists/settings/index.php.md):44 |  | insert | `analyst_id`, `preference_key`, `preference_value` |  |
| [includes/files/storage.php](/code/files/includes/files/storage.php.md):164 | `filesSavePref` | insert | `analyst_id`, `preference_key`, `preference_value`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/calendar/feed.php](/code/files/api/calendar/feed.php.md) | L42 |  |
| [api/calendar/get_feed_url.php](/code/files/api/calendar/get_feed_url.php.md) | L35 |  |
| [api/system/get_user_preference.php](/code/files/api/system/get_user_preference.php.md) | L27 |  |
| [api/table-views/list.php](/code/files/api/table-views/list.php.md) | L58 |  |
| [api/tickets/get_schedule_feed_url.php](/code/files/api/tickets/get_schedule_feed_url.php.md) | L61 |  |
| [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md) | L63 |  |
| [checklists/includes/header.php](/code/files/checklists/includes/header.php.md) | L25 |  |
| [checklists/settings/index.php](/code/files/checklists/settings/index.php.md) | L54 |  |
| [contracts/includes/header.php](/code/files/contracts/includes/header.php.md) | L31 |  |
| [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md) | scheduleFeedDetail L424 |  |
| [includes/files/storage.php](/code/files/includes/files/storage.php.md) | filesGetPrefs L142 |  |
| [includes/i18n.php](/code/files/includes/i18n.php.md) | I18n::initFromSession L121 |  |
| [includes/inbox_display.php](/code/files/includes/inbox_display.php.md) | inboxDisplayForAnalyst L164, inboxDisplayIsPersonal L196 |  |
| [includes/landing.php](/code/files/includes/landing.php.md) | landingRefreshCookieFromPreference L151 |  |
| [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) | notificationsTaskAssignedEmail L430 |  |
| [includes/services/notifications.php](/code/files/includes/services/notifications.php.md) | NotificationsService::preferences L258 |  |
| [includes/theme.php](/code/files/includes/theme.php.md) | Theme::active L128 |  |
| [includes/timezone.php](/code/files/includes/timezone.php.md) | Tz::init L49, DateFmt::init L231 |  |
| [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md) | renderWaffleMenuJS L779, renderWarRoomAlerts L1274 |  |
| [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md) | wtImpersonalOnMine L134, wtScopeFor L151 |  |
| [knowledge/index.php](/code/files/knowledge/index.php.md) | L32 |  |
| [morning-checks/index.php](/code/files/morning-checks/index.php.md) | L51 |  |
| [system/preferences/index.php](/code/files/system/preferences/index.php.md) | L106 |  |
| [tasks/calendar/index.php](/code/files/tasks/calendar/index.php.md) | L18 |  |
| [tasks/index.php](/code/files/tasks/index.php.md) | L46 |  |
| [tickets/calendar.php](/code/files/tickets/calendar.php.md) | L29 |  |
| [war-room/index.php](/code/files/war-room/index.php.md) | L57 |  |

