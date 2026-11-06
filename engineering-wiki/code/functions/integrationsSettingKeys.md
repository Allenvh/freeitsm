# integrationsSettingKeys()

The non-secret setting keys a provider declares, for use as a whitelist.

⚠️ This is what keeps `settings` safe to hand to the browser. The values live
in the same encrypted credentials blob as the API token, so anything reading
them out for display MUST filter by this list rather than emitting what it
finds — see `integrationsListConnections()`.

```php
function integrationsSettingKeys(string $providerKey): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 215.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$providerKey` | `string` |  | the provider key from `integrationsAvailableProviders()`, e.g. 'jira' or 'azuredevops' |

**Returns:** array key => default value

## Calls

[integrationsProviderMeta()](/code/functions/integrationsProviderMeta.md)

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsListConnections()](/code/functions/integrationsListConnections.md)
- [system/integrations/provider.php](/code/files/system/integrations/provider.php.md)

