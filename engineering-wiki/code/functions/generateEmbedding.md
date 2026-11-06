# generateEmbedding()

Generate embedding for text using OpenAI API

```php
function generateEmbedding($text, $apiKey)
```

Defined in [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md), line 81.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` |  |  | the text to embed; cut to 30,000 bytes |
| `$apiKey` |  |  | the OpenAI API key, in plain text |

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md)

