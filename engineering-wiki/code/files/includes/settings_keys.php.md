# includes/settings_keys.php

> PHP - 283 lines

Who owns each key in `system_settings`.

---------------------------------------------------------------------------
WHY THIS EXISTS
---------------------------------------------------------------------------
api/settings/save_system_settings.php is a GENERIC key/value writer: it loops over
whatever keys the caller posts and writes them. Five settings pages use it —
Asset Management (warranty/vCenter/Intune), Tickets (general), and the System
colours/security/SSO areas — because those tabs have no endpoint of their own.

That made it impossible to authorise properly. A guard at the top of the file is
meaningless when it's the same file for all five callers, so the endpoint checked
only that you were a logged-in analyst. Any analyst — including one whose module
access was a single unrelated module — could therefore POST any key and overwrite
any system setting: vCenter and Intune credentials, the SSO configuration, or the
brute-force lockout policy (max_failed_logins, lockout_duration_minutes).

So the capability attaches to the KEY, not the file. This map is the authority:
save_system_settings.php looks up each posted key and demands its owner's access.

---------------------------------------------------------------------------
DENY BY DEFAULT, AND FAIL LOUD
---------------------------------------------------------------------------
A key that is NOT in this map is REJECTED. That matters twice over:

 1. `system_settings` holds ~88 keys, but only the ones below are legitimately
    written through this endpoint. The rest — branding, AI provider keys, cron
    tokens, module_permission_mode, local_login_enabled — have their own dedicated,
    guarded endpoints. There is no reason for a generic writer to reach them, and
    before this map, it could.

 2. If a key is missed when a new tab is built, the symptom is that saving that tab
    ERRORS VISIBLY. The alternative failure — silently accepting an unowned key —
    is how this hole existed in the first place. Loud beats open.

---------------------------------------------------------------------------
ROLLOUT
---------------------------------------------------------------------------
'cap' is the per-tab capability that will own the key once its module is converted
(docs/design/rbac.md §9, phase 3b). Until then it is null and the key falls back to
'module' — plain Layer 1 module access, which is exactly what reaching these settings
pages requires today, so nothing breaks. When a module converts, fill in its 'cap'.

Module 'system' resolves to is_admin via `analystCanAccessModule()`, so the System
areas are admin-only with no special-casing here.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [settingKeyOwners()](/code/functions/settingKeyOwners.md) | 58 | Exact key -> owner. 'tab' is documentation: it names the settings tab the key belongs to, so the eventual per-tab capability is obvious when the module converts. |
| [settingKeyPrefixes()](/code/functions/settingKeyPrefixes.md) | 180 | Prefix -> owner, for keys generated at runtime rather than named in code. |
| [settingKeyOwner()](/code/functions/settingKeyOwner.md) | 194 | Who owns this setting key? null = nobody, so it may not be written through the generic endpoint. Exact matches win over prefixes. |
| [analystCanManageAiNamespace()](/code/functions/analystCanManageAiNamespace.md) | 223 | May this analyst manage the AI configuration for a namespace ('knowledge_ai', 'cmdb_ai', 'tickets_reply_cleanup', …)? |
| [analystCanManageAnyAiNamespace()](/code/functions/analystCanManageAnyAiNamespace.md) | 239 | May this analyst manage the AI settings of ANY namespace? |
| [requireAiNamespaceJson()](/code/functions/requireAiNamespaceJson.md) | 253 | The API twin: refuse with a 403 unless this analyst may manage the namespace's AI config. |
| [analystCanWriteSettingKey()](/code/functions/analystCanWriteSettingKey.md) | 273 | May this analyst write this setting key? |

## How it hangs together

**Includes:** [includes/capabilities.php](/code/files/includes/capabilities.php.md), [includes/ai_settings.php](/code/files/includes/ai_settings.php.md)

**Included by:** [api/knowledge/save_email_settings.php](/code/files/api/knowledge/save_email_settings.php.md), [api/settings/save_system_settings.php](/code/files/api/settings/save_system_settings.php.md), [api/system/ai/get_settings.php](/code/files/api/system/ai/get_settings.php.md), [api/system/ai/openrouter_models.php](/code/files/api/system/ai/openrouter_models.php.md), [api/system/ai/save_settings.php](/code/files/api/system/ai/save_settings.php.md), [api/system/ai/test_connection.php](/code/files/api/system/ai/test_connection.php.md), [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md), [api/tasks/save_settings.php](/code/files/api/tasks/save_settings.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md)

**Calls:** [aiSettingsRegistry()](/code/functions/aiSettingsRegistry.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystHasCapability()](/code/functions/analystHasCapability.md), [analystIsAdmin()](/code/functions/analystIsAdmin.md), [settingsManifests()](/code/functions/settingsManifests.md)

