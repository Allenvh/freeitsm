# slackWebhookUrlProblem()

Is this install's public URL usable as a Slack request URL?

Slack will not accept http://, and it cannot reach localhost — so an install
that has not been given a real public address can never complete setup. Saying
so here turns a baffling "Your URL didn't respond with the value of the
challenge parameter" in Slack's UI into a sentence that names the problem.

```php
function slackWebhookUrlProblem(string $webhookUrl): string
```

Defined in [includes/messaging/slack_manifest.php](/code/files/includes/messaging/slack_manifest.php.md), line 126.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$webhookUrl` | `string` |  | the public webhook URL that Slack will be given |

**Returns:** string '' when it looks usable, otherwise the reason it will not work

## Called from

- [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md)
- [api/messaging/slack_manifest.php](/code/files/api/messaging/slack_manifest.php.md)

