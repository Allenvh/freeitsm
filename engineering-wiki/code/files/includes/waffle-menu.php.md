# includes/waffle-menu.php

> PHP - 2377 lines

Shared Waffle Menu Component Cross-module navigation menu for ITSM system

Required variables before including:
  `$path_prefix` - Path to root (e.g., '../' or '../../')
  `$current_module` - Current module identifier (tickets, assets, knowledge, changes, calendar, morning-checks, reporting)

Optional variables:
  `$analyst_name` - User's display name (defaults to 'Analyst')

## Functions

| Function | Line | What it does |
|---|--:|---|
| [renderWaffleMenuButton()](/code/functions/renderWaffleMenuButton.md) | 664 | Output the waffle menu button and panel |
| [renderWaffleMenuPanel()](/code/functions/renderWaffleMenuPanel.md) | 685 | Renders the waffle drawer: the module launcher (System for administrators only, every other module filtered by the analyst's allowed modules) and the Recent tab, whose trail ships empty and is fetched the first time it opens. |
| [renderWaffleMenuJS()](/code/functions/renderWaffleMenuJS.md) | 758 | Outputs the scripts every analyst page shares: the analyst's toast, chime, search-panel and desktop-notification preferences as window globals (one user_preferences query), toast/confirm/clipboard, the command palette with the modules this analyst may see, and the waffle drawer's own JS. |
| [renderNotificationBell()](/code/functions/renderNotificationBell.md) | 1242 | The global notification bell (discussion #55). |
| [renderWarRoomAlerts()](/code/functions/renderWarRoomAlerts.md) | 1264 | Renders the header's War Room alert button and panel, which poll api/war-room/alerts.php every minute and stay hidden until something is waiting. Not polled on the War Room page itself; desktop popups only when the analyst's warroom_desktop_alerts preference is on. |
| [renderHeaderRight()](/code/functions/renderHeaderRight.md) | 1477 | Renders the right-hand side of the analyst header: the "Back to" link, the company switcher, the notification bell, War Room alerts, the mail-check button, the initials avatar and its account menu (preferences, password, MFA, the portal link when the analyst could sign in there, appearance, log out), plus the password and MFA dialogs and their script. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 779 | `renderWaffleMenuJS` | read [user_preferences](/code/tables/user_preferences.md) |  |
| 1274 | `renderWarRoomAlerts` | read [user_preferences](/code/tables/user_preferences.md) |  |
| 1510 | `renderHeaderRight` | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md), [includes/module-colors.php](/code/files/includes/module-colors.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/documents_panel.php](/code/files/includes/documents_panel.php.md), [includes/notification_bell.php](/code/files/includes/notification_bell.php.md), [includes/tenancy-switcher.php](/code/files/includes/tenancy-switcher.php.md), [includes/back_link.php](/code/files/includes/back_link.php.md)

**Included by:** [change-management/includes/header.php](/code/files/change-management/includes/header.php.md), [index.php](/code/files/index.php.md), [problem-management/includes/header.php](/code/files/problem-management/includes/header.php.md)

**Loads scripts:** `includes/toast.js`

**Calls:** [backLinkHtml()](/code/functions/backLinkHtml.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [documentsPanelAssets()](/code/functions/documentsPanelAssets.md), [getModuleColors()](/code/functions/getModuleColors.md), [notificationBellRender()](/code/functions/notificationBellRender.md), [renderTenantSwitcher()](/code/functions/renderTenantSwitcher.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md), [t()](/code/functions/t.md)

