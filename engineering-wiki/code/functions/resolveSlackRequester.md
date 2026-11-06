# resolveSlackRequester()

Work out who a Slack message is from. Returns [userId|null, displayName].

⚠️ Halo's Slack integration requires the person's Slack email to equal their
Halo email, which quietly fails for guests, contractors and anyone whose Slack
account is a personal address. We do the lookup but never *depend* on it:

  1. ask Slack for the profile (needs users:read + users:read.email)
  2. if it gives an email we already know, the ticket belongs to that person
  3. otherwise fall back to a channel pseudo-user — and NAME the case, so the
     ticket says "Sam Okafor (Slack)" or "Slack user @U08ABCDEF" rather than
     something anonymous that an analyst cannot act on

Every failure path still returns a usable requester. Losing the identity must
never lose the ticket.

```php
function resolveSlackRequester(PDO $conn, array $channel, string $slackUserId): array
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 346.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the Slack messaging_channels row, decrypted |
| `$slackUserId` | `string` |  | the sender's Slack user id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 385 | `resolveSlackRequester` | **update** [users](/code/tables/users.md) (`display_name`) |  |

## Calls

[getOrCreateChannelUser()](/code/functions/getOrCreateChannelUser.md), [messagingKnownPersonByEmail()](/code/functions/messagingKnownPersonByEmail.md), [messagingProvider()](/code/functions/messagingProvider.md)

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)

