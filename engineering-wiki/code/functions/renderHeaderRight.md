# renderHeaderRight()

Renders the right-hand side of the analyst header: the "Back to" link, the company switcher, the notification bell, War Room alerts, the mail-check button, the initials avatar and its account menu (preferences, password, MFA, the portal link when the analyst could sign in there, appearance, log out), plus the password and MFA dialogs and their script.

```php
function renderHeaderRight($analyst_name, $path_prefix)
```

Defined in [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md), line 1477.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$analyst_name` |  |  | the signed-in analyst's display name, for the avatar initials and the menu |
| `$path_prefix` |  |  | relative path from the page to the app root, e.g. '../' |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1510 | `renderHeaderRight` | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |

## Calls

[backLinkHtml()](/code/functions/backLinkHtml.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [renderNotificationBell()](/code/functions/renderNotificationBell.md), [renderTenantSwitcher()](/code/functions/renderTenantSwitcher.md), [renderWarRoomAlerts()](/code/functions/renderWarRoomAlerts.md), [t()](/code/functions/t.md)

## Called from

- [asset-management/includes/header.php](/code/files/asset-management/includes/header.php.md)
- [calendar/includes/header.php](/code/files/calendar/includes/header.php.md)
- [change-management/includes/header.php](/code/files/change-management/includes/header.php.md)
- [checklists/includes/header.php](/code/files/checklists/includes/header.php.md)
- [cmdb/includes/header.php](/code/files/cmdb/includes/header.php.md)
- [contracts/includes/header.php](/code/files/contracts/includes/header.php.md)
- [domains/includes/header.php](/code/files/domains/includes/header.php.md)
- [files/includes/header.php](/code/files/files/includes/header.php.md)
- [forms/includes/header.php](/code/files/forms/includes/header.php.md)
- [index.php](/code/files/index.php.md)
- [knowledge/includes/header.php](/code/files/knowledge/includes/header.php.md)
- [lms/includes/header.php](/code/files/lms/includes/header.php.md)
- [morning-checks/includes/header.php](/code/files/morning-checks/includes/header.php.md)
- [network-mapper/includes/header.php](/code/files/network-mapper/includes/header.php.md)
- [people/includes/header.php](/code/files/people/includes/header.php.md)
- [problem-management/includes/header.php](/code/files/problem-management/includes/header.php.md)
- [process-mapper/includes/header.php](/code/files/process-mapper/includes/header.php.md)
- [projects/includes/header.php](/code/files/projects/includes/header.php.md)
- [reporting/includes/header.php](/code/files/reporting/includes/header.php.md)
- [service-status/includes/header.php](/code/files/service-status/includes/header.php.md)
- [software/includes/header.php](/code/files/software/includes/header.php.md)
- [system/includes/header.php](/code/files/system/includes/header.php.md)
- [tasks/includes/header.php](/code/files/tasks/includes/header.php.md)
- [tickets/includes/header.php](/code/files/tickets/includes/header.php.md)
- [war-room/includes/header.php](/code/files/war-room/includes/header.php.md)
- [watchtower/includes/header.php](/code/files/watchtower/includes/header.php.md)
- [workflow/includes/header.php](/code/files/workflow/includes/header.php.md)

