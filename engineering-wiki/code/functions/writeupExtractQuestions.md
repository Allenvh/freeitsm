# writeupExtractQuestions()

Pull the "- " question lines out of a NOT_ENOUGH body.

```php
function writeupExtractQuestions(string $body): array
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 823.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `string` |  | the body of a NOT_ENOUGH response, from `writeupParseResponse()` |

**Returns:** `array`

## Called from

- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)

