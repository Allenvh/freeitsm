# slackManifestScopes()

The scopes we ask for, and — just as importantly — why.

Kept minimal on purpose: a Slack admin reads this list before approving the
app, and every scope that is not obviously necessary is a reason to say no.
Notably absent: channels:manage (creating channels), users:write, and
anything touching files:write. Those belong to features we have not built.

```php
function slackManifestScopes(): array
```

Defined in [includes/messaging/slack_manifest.php](/code/files/includes/messaging/slack_manifest.php.md), line 34.

**Returns:** `array`

## Called from

- [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md)
- [includes/messaging/slack_manifest.php](/code/files/includes/messaging/slack_manifest.php.md) - in [slackBuildManifest()](/code/functions/slackBuildManifest.md)
- [system/integrations/slack.php](/code/files/system/integrations/slack.php.md)

