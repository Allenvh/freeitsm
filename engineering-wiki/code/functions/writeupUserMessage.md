# writeupUserMessage()

Build the user message for a single-ticket write-up.

```php
function writeupUserMessage(array $bundle, string $answers = ''): string
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 755.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$bundle` | `array` |  | the ticket bundle from `writeupTicketBundle()` - only 'text' is used |
| `$answers` | `string` | `''` | the analyst's answers to the model's questions; '' for a first attempt |

**Returns:** `string`

## Called from

- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)

