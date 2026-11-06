# system/preferences/index.php

> PHP - 2058 lines

System - Preferences (per-browser + per-analyst settings)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 106 |  | read [user_preferences](/code/tables/user_preferences.md) |  |
| 128 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md), [includes/landing.php](/code/files/includes/landing.php.md), [includes/calendar_sync/calendar_sync.php](/code/files/includes/calendar_sync/calendar_sync.php.md), [includes/services/notifications.php](/code/files/includes/services/notifications.php.md), [includes/subscribe_modal.php](/code/files/includes/subscribe_modal.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/qrcode.min.js](/code/files/assets/js/qrcode.min.js.md), [assets/js/subscribe.js](/code/files/assets/js/subscribe.js.md), [assets/js/tinymce/tinymce.min.js](/code/files/assets/js/tinymce/tinymce.min.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [landingInstallDefault()](/code/functions/landingInstallDefault.md), [pageTitle()](/code/functions/pageTitle.md), [renderSubscribeModal()](/code/functions/renderSubscribeModal.md), [scheduleFeedAllowed()](/code/functions/scheduleFeedAllowed.md), [t()](/code/functions/t.md)

