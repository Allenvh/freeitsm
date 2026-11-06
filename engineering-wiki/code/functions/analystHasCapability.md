# analystHasCapability()

Does this analyst hold a capability? Authoritative (DB-checked). is_admin is always true. Use this for server-side enforcement.

```php
function analystHasCapability(PDO $conn, int $analystId, string $capability): bool
```

Defined in [includes/rbac.php](/code/files/includes/rbac.php.md), line 96.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$capability` | `string` |  | the capability key, a Cap:: constant such as Cap::PROJECTS_MANAGE |

**Returns:** `bool`

## Calls

[analystIsAdmin()](/code/functions/analystIsAdmin.md), [getAnalystCapabilities()](/code/functions/getAnalystCapabilities.md)

## Called from

- [api/messaging/get_channels.php](/code/files/api/messaging/get_channels.php.md)
- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)
- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [api/projects/reports.php](/code/files/api/projects/reports.php.md)
- [api/projects/settings.php](/code/files/api/projects/settings.php.md)
- [api/projects/templates.php](/code/files/api/projects/templates.php.md)
- [api/war-room/channels.php](/code/files/api/war-room/channels.php.md) - in [warRoomMayManage()](/code/functions/warRoomMayManage.md)
- [api/war-room/message.php](/code/files/api/war-room/message.php.md)
- [api/watchtower/save_settings.php](/code/files/api/watchtower/save_settings.php.md)
- [files/index.php](/code/files/files/index.php.md)
- [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md) - in [domainHasCap()](/code/functions/domainHasCap.md)
- [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) - in [filesHasCap()](/code/functions/filesHasCap.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeViewerHasAdminFloor()](/code/functions/knowledgeViewerHasAdminFloor.md)
- [includes/lms_access.php](/code/files/includes/lms_access.php.md) - in [lmsCanManage()](/code/functions/lmsCanManage.md)
- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolAllowed()](/code/functions/mcpToolAllowed.md)
- [includes/projects/settings.php](/code/files/includes/projects/settings.php.md) - in [projectIsManager()](/code/functions/projectIsManager.md)
- [includes/rbac.php](/code/files/includes/rbac.php.md) - in [requireCapability()](/code/functions/requireCapability.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::assertManage`
- [includes/settings_keys.php](/code/files/includes/settings_keys.php.md) - in [analystCanWriteSettingKey()](/code/functions/analystCanWriteSettingKey.md)
- [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md) - in [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolAllowed()](/code/functions/warbotToolAllowed.md)
- [knowledge/assistant/index.php](/code/files/knowledge/assistant/index.php.md)
- [lms/includes/header.php](/code/files/lms/includes/header.php.md)
- [lms/index.php](/code/files/lms/index.php.md)
- [lms/my-courses.php](/code/files/lms/my-courses.php.md)
- [lms/player.php](/code/files/lms/player.php.md)
- [lms/settings/index.php](/code/files/lms/settings/index.php.md)
- [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)
- [war-room/index.php](/code/files/war-room/index.php.md)

