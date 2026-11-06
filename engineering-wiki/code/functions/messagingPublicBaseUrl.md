# messagingPublicBaseUrl()

The public base URL (scheme://host[:port]) used to build webhook URLs. Uses the admin-set 'messaging_public_base_url' system setting when present, otherwise derives it from the current request (honouring ngrok/tunnel proxy headers).

```php
function messagingPublicBaseUrl(PDO $conn): string
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 118.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 121 | `messagingPublicBaseUrl` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [includes/messaging/TeamsPackage.php](/code/files/includes/messaging/TeamsPackage.php.md) - in [teamsPackageBuild()](/code/functions/teamsPackageBuild.md)
- [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md) - in [messagingWebhookUrl()](/code/functions/messagingWebhookUrl.md), [messagingOutboundMediaUrl()](/code/functions/messagingOutboundMediaUrl.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatBaseUrl()](/code/functions/webchatBaseUrl.md)

