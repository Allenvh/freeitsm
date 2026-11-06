# warRoomResolveMentions()

Work out who a message names, and record it.

🔑 RESOLVED FROM THE TEXT, SERVER-SIDE — the client sends no list of ids. That
is forced by how the composer is meant to work: you type `@`, pick a name, and
may then BACKSPACE down to just the first name. Anything the client resolved at
pick time is wrong the moment the text is edited, and a mention typed by hand
without ever touching the autocomplete would not resolve at all. The text is the
only thing that survives both, so the text is what we read.

The body is stored EXACTLY AS TYPED. No `@[39]` tokens: the raw message has to
stay readable in search results and in the transcript the AI summarises, and a
stored token would leak into both.

Matching is longest-first, so "@Sarah Williams" wins over "@Sarah". Where a bare
first name is genuinely ambiguous — two Sarahs — EVERY match is notified. During
an incident two people looking is a far better failure than nobody looking.

⚠️ Recipients are filtered to analysts who can actually READ the channel. Naming
somebody who cannot see a private channel would otherwise put its name and a
snippet of it into their notifications panel.

```php
function warRoomResolveMentions(PDO $conn, int $channelId, int $authorId, string $body): array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 737.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the channel the message is in; only its readers can be mentioned |
| `$authorId` | `int` |  | the analyst who wrote it, never notified of their own message |
| `$body` | `string` |  | the message text as typed |

**Returns:** int[] the analyst ids notified

## Calls

[warRoomChannelAudience()](/code/functions/warRoomChannelAudience.md)

## Called from

- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomEditMessage()](/code/functions/warRoomEditMessage.md), [warRoomSend()](/code/functions/warRoomSend.md)

