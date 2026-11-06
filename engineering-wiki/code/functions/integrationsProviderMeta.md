# integrationsProviderMeta()

One registry entry, or null if the key is not a provider we ship.

```php
function integrationsProviderMeta(string $key): ?array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 230.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the provider key, e.g. 'jira' or 'azuredevops' |

**Returns:** `?array`

## Calls

[integrationsAvailableProviders()](/code/functions/integrationsAvailableProviders.md)

## Called from

- [api/integrations/save_connection.php](/code/files/api/integrations/save_connection.php.md)
- [api/integrations/test_connection.php](/code/files/api/integrations/test_connection.php.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsSettingKeys()](/code/functions/integrationsSettingKeys.md)
- [system/integrations/help.php](/code/files/system/integrations/help.php.md)
- [system/integrations/provider.php](/code/files/system/integrations/provider.php.md)
- [system/integrations/slack.php](/code/files/system/integrations/slack.php.md)
- [system/integrations/tika.php](/code/files/system/integrations/tika.php.md)

