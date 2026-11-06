# slackBuildManifest()

Build the manifest for one channel row.

```php
function slackBuildManifest(string $webhookUrl, string $appName = 'FreeITSM'): array
```

Defined in [includes/messaging/slack_manifest.php](/code/files/includes/messaging/slack_manifest.php.md), line 53.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$webhookUrl` | `string` |  | the channel's own public webhook URL |
| `$appName` | `string` | `'FreeITSM'` | what the app will be called in Slack |

**Returns:** `array`

## Calls

[slackManifestScopes()](/code/functions/slackManifestScopes.md)

## Called from

- [includes/messaging/slack_manifest.php](/code/files/includes/messaging/slack_manifest.php.md) - in [slackManifestJson()](/code/functions/slackManifestJson.md)

