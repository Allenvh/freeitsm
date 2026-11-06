# analystCanManageAiNamespace()

May this analyst manage the AI configuration for a namespace ('knowledge_ai', 'cmdb_ai', 'tickets_reply_cleanup', …)?

The AI settings panel is SHARED by seven modules and posts to one endpoint
(api/system/ai/*), passing its namespace. So — like the shared settings writer — a single
guard on that file cannot be right for seven different audiences, and until now it simply
demanded is_admin: fine, but it meant a module's AI tab could never be delegated.

A namespace's settings ARE system_settings keys ('<ns>_api_key' and friends), so this
reuses the ownership map rather than inventing a second mechanism:

  - the module has been CONVERTED (its manifest claims those keys) → the tab's capability;
  - it has NOT been converted yet                                  → is_admin, exactly as
    today, so nothing loosens by accident.

```php
function analystCanManageAiNamespace(PDO $conn, int $analystId, string $ns): bool
```

Defined in [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), line 223.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$ns` | `string` |  | the AI settings namespace, e.g. 'knowledge_ai', 'cmdb_ai', 'tickets_reply_cleanup' |

**Returns:** `bool`

## Calls

[analystCanWriteSettingKey()](/code/functions/analystCanWriteSettingKey.md), [analystIsAdmin()](/code/functions/analystIsAdmin.md), [settingKeyOwner()](/code/functions/settingKeyOwner.md)

## Called from

- [includes/settings_keys.php](/code/files/includes/settings_keys.php.md) - in [analystCanManageAnyAiNamespace()](/code/functions/analystCanManageAnyAiNamespace.md), [requireAiNamespaceJson()](/code/functions/requireAiNamespaceJson.md)

