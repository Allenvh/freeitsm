# sessionIsAdmin()

Cosmetic (UI-layer) admin check for pages: "should this analyst see System?". Reads `$_SESSION`['is_admin']; if absent (e.g. a session created before this upgrade) it self-heals by looking the flag up once and caching it in the session, so no login-path changes are needed and no admin is ever locked out. Requires a writable session (not read_and_close). For hard enforcement on APIs use `requireAdminJson()` instead, which re-checks the DB every call.

```php
function sessionIsAdmin(): bool
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 246.

**Returns:** `bool`

## Calls

[analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [api/messaging/get_channels.php](/code/files/api/messaging/get_channels.php.md)
- [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md)
- [api/system/ai/test_connection.php](/code/files/api/system/ai/test_connection.php.md)
- [api/tickets/manager_access.php](/code/files/api/tickets/manager_access.php.md) - in [maState()](/code/functions/maState.md)
- [files/index.php](/code/files/files/index.php.md)
- [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) - in [catalogueApprovalDecide()](/code/functions/catalogueApprovalDecide.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managerLinesEditable()](/code/functions/managerLinesEditable.md)
- [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md) - in [messagingAdminMayAdministerChannel()](/code/functions/messagingAdminMayAdministerChannel.md)
- [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md) - in [renderWaffleMenuPanel()](/code/functions/renderWaffleMenuPanel.md), [renderWaffleMenuJS()](/code/functions/renderWaffleMenuJS.md)
- [index.php](/code/files/index.php.md)
- [system/debug-tools/includes/tool-page.php](/code/files/system/debug-tools/includes/tool-page.php.md)
- [system/includes/header.php](/code/files/system/includes/header.php.md)

