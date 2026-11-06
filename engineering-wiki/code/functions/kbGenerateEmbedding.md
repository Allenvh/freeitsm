# kbGenerateEmbedding()

Embed a query with OpenAI's small embedding model. Returns the vector or null.

```php
function kbGenerateEmbedding(string $text, string $apiKey): ?array
```

Defined in [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md), line 49.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `string` |  | the text to embed; cut to 30,000 bytes first |
| `$apiKey` | `string` |  | the OpenAI API key, decrypted |

**Returns:** `?array`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapEmbedBatch()](/code/functions/gapEmbedBatch.md)
- [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md) - in [kbRetrieveArticles()](/code/functions/kbRetrieveArticles.md)

