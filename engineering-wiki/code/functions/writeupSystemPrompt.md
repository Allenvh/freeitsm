# writeupSystemPrompt()

The system prompt. One call does both jobs — judge, then write — because the judgement is worthless if a second call can overrule it, and because a single streamed response is a far better experience than a spinner followed by a verdict followed by another spinner.

The response protocol is a single first line, `VERDICT: ARTICLE` or
`VERDICT: NOT_ENOUGH`, so the front end can branch while still streaming the
rest token by token.

```php
function writeupSystemPrompt(string $mode = 'single'): string
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 647.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mode` | `string` | `'single'` | 'single' (one ticket), 'cluster' (a recurring question) or 'answers' (the analyst has replied) |

**Returns:** `string`

## Calls

[writeupProtocolBlock()](/code/functions/writeupProtocolBlock.md)

## Called from

- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)

