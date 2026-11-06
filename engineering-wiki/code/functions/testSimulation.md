# testSimulation()

Run a synthetic inbound message through ingest, then clean up everything it created.

```php
function testSimulation(PDO $conn, array $channel): array
```

Defined in [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md), line 160.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the messaging_channels row as loaded by `loadMessagingChannel()` |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 176 | `testSimulation` | read [messaging_channels](/code/tables/messaging_channels.md) |  |
| 203 | `testSimulation` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[ingestInboundMessage()](/code/functions/ingestInboundMessage.md), [simCleanup()](/code/functions/simCleanup.md)

## Called from

- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md)

