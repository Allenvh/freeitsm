# resolveDirectoryRequester()

Who a Teams or Mattermost message is from. Returns [userId|null, displayName].

Added after PR #166 was merged, on the contributor's suggestion: until then
every Teams and Mattermost person became a contact of their own
("…@teams.local"), even when FreeITSM already held them by email, so their
chat tickets sat apart from everything else of theirs. Now, as on Slack:

  1. ask the platform for the person's name and email (lookupUser())
  2. an email FreeITSM already holds -> the ticket is that person's
  3. otherwise the channel's own contact, as before - keyed on the platform
     id, which never changes - with the real name filled in

Only WHO raised the ticket changes. Which ticket a message joins is still
decided by the platform id (findOpenChannelTicket()), because an id never
changes and an email can. Every failure path still returns a requester.

```php
function resolveDirectoryRequester(
    PDO $conn,
    array $channel,
    array $msg,
    string $from,
    string $displayName
): array
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 446.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the Teams or Mattermost messaging_channels row, decrypted |
| `$msg` | `array` |  | the normalised message (Teams reads 'to' and 'sender_id') |
| `$from` | `string` |  | the normalised sender id - the key for the channel's own contact |
| `$displayName` | `string` |  | the name to use when the platform gives none |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 475 | `resolveDirectoryRequester` | **update** [users](/code/tables/users.md) (`display_name`) |  |

## Calls

[getOrCreateChannelUser()](/code/functions/getOrCreateChannelUser.md), [messagingKnownPersonByEmail()](/code/functions/messagingKnownPersonByEmail.md), [messagingProvider()](/code/functions/messagingProvider.md)

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)

