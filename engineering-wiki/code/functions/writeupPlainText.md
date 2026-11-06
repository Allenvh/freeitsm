# writeupPlainText()

Strip HTML, entities, quoted reply chains and signatures down to readable text.

```php
function writeupPlainText(?string $html, int $maxChars = 4000): string
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$html` | `?string` |  | an email body or note as stored (HTML or plain); null or '' gives '' |
| `$maxChars` | `int` | `4000` | the most characters kept before an ellipsis is added |

**Returns:** `string`

## Called from

- [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md) - in [writeupTicketBundle()](/code/functions/writeupTicketBundle.md), [writeupEmbeddingText()](/code/functions/writeupEmbeddingText.md)

