# knowledgeOpenAiKey()

The OpenAI key used for ARTICLE embeddings, reused deliberately.

⚠️ Vectors are only comparable within one model. Ticket embeddings and article
embeddings must come from the same key and the same model or every similarity
score is silently meaningless — no error, just wrong answers. That is why this
reads knowledge_openai_api_key rather than introducing its own setting.

```php
function knowledgeOpenAiKey(PDO $conn): string
```

Defined in [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), line 30.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 | `knowledgeOpenAiKey` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/knowledge/analyse_gaps.php](/code/files/api/knowledge/analyse_gaps.php.md)
- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapAnalyse()](/code/functions/gapAnalyse.md)

