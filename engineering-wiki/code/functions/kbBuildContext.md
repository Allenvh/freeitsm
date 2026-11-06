# kbBuildContext()

Turn retrieved articles into a plain-text context block for the AI system prompt.

```php
function kbBuildContext(array $articles): string
```

Defined in [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md), line 178.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$articles` | `array` |  | the 'articles' list from `kbRetrieveArticles()` - rows with title and HTML body |

**Returns:** `string`

## Called from

- [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md) - in [webchatAiReply()](/code/functions/webchatAiReply.md)

