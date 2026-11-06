# findOpenChannelTicket()

Find the most recent OPEN (not closed, not trashed) ticket for this conversation, so repeat messages thread into one ticket.

⚠️ What counts as "this conversation" is per-channel:

  phone channels — the SENDER. A person has one running conversation with the
                   service desk, so their number identifies it.
  slack          — the THREAD. One person can have five unrelated threads open
                   at once, and matching on the sender would collapse all five
                   onto one ticket. `$replyAddress` is "C08HELP:1719500000.0001",
                   which is exactly the thread.

```php
function findOpenChannelTicket(
    PDO $conn,
    string $from,
    string $channelType,
    string $replyAddress = '',
    ?int $channelId = null
): ?int
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 293.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$from` | `string` |  | the normalised sender id (phone number, chat id or platform user id) |
| `$channelType` | `string` |  | the channel type, e.g. 'whatsapp', 'slack', 'telegram', 'teams', 'mattermost' |
| `$replyAddress` | `string` | `''` | the Slack thread address "<channel>:<threadTs>"; only read for Slack, '' otherwise |
| `$channelId` | `?int` | `null` | the messaging_channels id; Telegram, Teams and Mattermost only match within it |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 308 | `findOpenChannelTicket` | read [tickets](/code/tables/tickets.md), read [emails](/code/tables/emails.md) |  |

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md), [messagingTelegramLinkPhone()](/code/functions/messagingTelegramLinkPhone.md)

## Traps

> [!WARNING]
> **Line 302, in `findOpenChannelTicket`:** a new channel type whose sender id is not unique per channel must be added here. Mattermost (PR #166) is one: a user id is the same in every configuration on one server, so two companies' support channels would share a person's thread - another company's ticket - without this.

