# webchatAiReply()

Draft an AI answer to `$question`, given the recent `$history` (array of ['sender','body']). Returns ['ok'=>bool, 'answer'=>string, 'articles'=>[{id,title}], 'error'=>?string].

`$tenantId` is the widget's company (messaging_channels.tenant_id) — the SAME value
that already decides which company the resulting ticket belongs to. Without it, a
visitor on one client's website could be answered out of another client's articles.
NULL means the widget has no company, which scopes to shared articles only.

The audience is always Audience::PUBLIC and is not a parameter: whoever is typing
into a website chat box is an anonymous stranger. They gave a name and an email;
we verified neither.

```php
function webchatAiReply(
    PDO $conn,
    string $question,
    array $history = [],
    ?int $tenantId = null
): array
```

Defined in [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md), line 44.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$question` | `string` |  | the visitor's latest message - untrusted |
| `$history` | `array` | `[]` | earlier messages, each ['sender' => 'visitor'\|..., 'body' => text]; the last six are used |
| `$tenantId` | `?int` | `null` | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md), [kbBuildContext()](/code/functions/kbBuildContext.md), [kbRetrieveArticles()](/code/functions/kbRetrieveArticles.md)

## Called from

- [api/webchat/send.php](/code/files/api/webchat/send.php.md)

