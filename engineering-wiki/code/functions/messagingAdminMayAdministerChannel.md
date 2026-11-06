# messagingAdminMayAdministerChannel()

Authorise a request that acts on ONE messaging channel.

⚠️ Two doors lead to these endpoints and they are gated differently:

  Tickets → Settings → Messaging / Web chat  — RBAC, Cap::TICKETS_MESSAGING
  System  → Integrations → Slack             — administrators only

So an admin without the Tickets messaging capability must be able to
administer a SLACK channel and nothing else. Widening the capability instead
would hand them a WhatsApp channel's credentials, which is precisely what that
capability exists to withhold.

Returns true when the caller may act on this channel by virtue of being an
admin working on Slack; false means "fall through to the normal capability
check", which then produces the standard error.

```php
function messagingAdminMayAdministerChannel(PDO $conn, int $channelId): bool
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 499.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the messaging_channels id being acted on; 0 or less is always refused |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 505 | `messagingAdminMayAdministerChannel` | read [messaging_channels](/code/tables/messaging_channels.md) |  |

## Calls

[sessionIsAdmin()](/code/functions/sessionIsAdmin.md)

## Called from

- [api/messaging/delete_channel.php](/code/files/api/messaging/delete_channel.php.md)
- [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md)
- [api/messaging/slack_manifest.php](/code/files/api/messaging/slack_manifest.php.md)
- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md)

