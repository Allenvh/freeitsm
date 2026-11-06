# analystIsAdmin()

Is this analyst an administrator? Admins are the only accounts allowed into the System module (analyst/team/company management, SSO, security, DB verify, etc.). Authoritative DB check — use this for server-side enforcement (no session lag).

```php
function analystIsAdmin(PDO $conn, int $analystId): bool
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 205.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 217 | `analystIsAdmin` | read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/system/browse_directory_ous.php](/code/files/api/system/browse_directory_ous.php.md)
- [api/system/debug-tools/D001_demo_core_import.php](/code/files/api/system/debug-tools/D001_demo_core_import.php.md)
- [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md)
- [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md)
- [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md)
- [api/system/debug-tools/D006_ssl_verify.php](/code/files/api/system/debug-tools/D006_ssl_verify.php.md)
- [api/system/debug-tools/D007_search_corpus.php](/code/files/api/system/debug-tools/D007_search_corpus.php.md)
- [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md)
- [api/system/debug-tools/D009_guarded_paths.php](/code/files/api/system/debug-tools/D009_guarded_paths.php.md)
- [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md)
- [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md)
- [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md)
- [api/system/debug-tools/D013_storage_persistence.php](/code/files/api/system/debug-tools/D013_storage_persistence.php.md)
- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md)
- [api/system/debug-tools/D015_carddav_health.php](/code/files/api/system/debug-tools/D015_carddav_health.php.md)
- [api/system/debug-tools/D016_carddav_drift.php](/code/files/api/system/debug-tools/D016_carddav_drift.php.md)
- [api/system/debug-tools/D017_config_completeness.php](/code/files/api/system/debug-tools/D017_config_completeness.php.md)
- [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md)
- [api/system/get_directory_sync_log.php](/code/files/api/system/get_directory_sync_log.php.md)
- [api/system/run_directory_sync.php](/code/files/api/system/run_directory_sync.php.md)
- [api/system/test_directory_mapping.php](/code/files/api/system/test_directory_mapping.php.md)
- [api/tickets/user_groups.php](/code/files/api/tickets/user_groups.php.md) - in [handleList()](/code/functions/handleList.md), [handleGet()](/code/functions/handleGet.md)
- [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md) - in [apiAnalystsRequireProvisioner()](/code/functions/apiAnalystsRequireProvisioner.md)
- [includes/functions.php](/code/files/includes/functions.php.md) - in [sessionIsAdmin()](/code/functions/sessionIsAdmin.md), [requireAdminJson()](/code/functions/requireAdminJson.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md)
- [includes/rbac.php](/code/files/includes/rbac.php.md) - in [getAnalystCapabilities()](/code/functions/getAnalystCapabilities.md), [analystHasCapability()](/code/functions/analystHasCapability.md)
- [includes/settings_keys.php](/code/files/includes/settings_keys.php.md) - in [analystCanManageAiNamespace()](/code/functions/analystCanManageAiNamespace.md)
- [setup/index.php](/code/files/setup/index.php.md)
- [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md)
- [system/sso/carddav.php](/code/files/system/sso/carddav.php.md)
- [system/sso/provider.php](/code/files/system/sso/provider.php.md)

