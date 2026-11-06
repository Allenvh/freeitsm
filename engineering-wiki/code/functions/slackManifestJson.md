# slackManifestJson()

The manifest as the JSON string a person pastes into Slack. Pretty-printed because a human reads it before pasting, and unescaped slashes because an escaped URL (https:\/\/…) looks broken and invites "fixing".

```php
function slackManifestJson(string $webhookUrl, string $appName = 'FreeITSM'): string
```

Defined in [includes/messaging/slack_manifest.php](/code/files/includes/messaging/slack_manifest.php.md), line 107.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$webhookUrl` | `string` |  | the channel's own public webhook URL |
| `$appName` | `string` | `'FreeITSM'` | what the app will be called in Slack (trimmed to 35 characters; empty gives FreeITSM) |

**Returns:** `string`

## Calls

[slackBuildManifest()](/code/functions/slackBuildManifest.md)

## Called from

- [api/messaging/slack_manifest.php](/code/files/api/messaging/slack_manifest.php.md)

