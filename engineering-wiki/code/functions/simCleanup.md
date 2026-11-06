# simCleanup()

Remove everything a simulation created (ticket, its messages, the placeholder user) and restore the channel stamp.

```php
function simCleanup(
    PDO $conn,
    ?int $ticketId,
    string $sender,
    string $channelType,
    int $channelId,
    $prevStamp
): void
```

Defined in [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md), line 221.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `?int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$sender` | `string` |  | the fake sender the simulation used (a phone number or Telegram chat id) |
| `$channelType` | `string` |  | the channel type, e.g. 'whatsapp' or 'telegram' - picks how `$sender` is normalised |
| `$channelId` | `int` |  | the messaging_channels id whose last-inbound stamp is restored |
| `$prevStamp` |  |  | the channel's last_inbound_datetime before the test; empty restores NULL |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 225 | `simCleanup` | **delete** [emails](/code/tables/emails.md) |  |
| 226 | `simCleanup` | **delete** [tickets](/code/tables/tickets.md) |  |
| 232 | `simCleanup` | read [users](/code/tables/users.md) |  |
| 236 | `simCleanup` | read [tickets](/code/tables/tickets.md) |  |
| 239 | `simCleanup` | **delete** [users](/code/tables/users.md) |  |
| 243 | `simCleanup` | **update** [messaging_channels](/code/tables/messaging_channels.md) (`last_inbound_datetime`) |  |

## Calls

[normaliseChannelIdentifier()](/code/functions/normaliseChannelIdentifier.md)

## Called from

- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md) - in [testSimulation()](/code/functions/testSimulation.md)

