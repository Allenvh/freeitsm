# warbotAnswer()

Answer a question. Returns the text to post, or null if there is nothing to say.

```php
function warbotAnswer(
    PDO $conn,
    int $analystId,
    int $channelId,
    string $question,
    array $recent = []
): array
```

Defined in [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md), line 201.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id the question was asked in, for its name in the prompt |
| `$question` | `string` |  | the message addressed to Warbot, with the @Warbot removed |
| `$recent` | `array` | `[]` | recent messages for context: [['author'=>…,'body'=>…], …] |

**Returns:** `array`

## Calls

[aiProviderChatTools()](/code/functions/aiProviderChatTools.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [warbotHelpText()](/code/functions/warbotHelpText.md), [warbotLooksLikeStatusNotice()](/code/functions/warbotLooksLikeStatusNotice.md), [warbotNoBrain()](/code/functions/warbotNoBrain.md), [warbotRunTool()](/code/functions/warbotRunTool.md), [warbotSystemPrompt()](/code/functions/warbotSystemPrompt.md), [warbotToolsFor()](/code/functions/warbotToolsFor.md), [warbotTryCommand()](/code/functions/warbotTryCommand.md), [warRoomChannel()](/code/functions/warRoomChannel.md), [warRoomChannelName()](/code/functions/warRoomChannelName.md)

## Called from

- [api/war-room/warbot.php](/code/files/api/war-room/warbot.php.md)

