# writeupSubjectTokens()

Normalise a subject into comparable tokens: strip Re:/Fwd:, ticket references, numbers, punctuation and stop words, then stem what is left.

Numbers go deliberately. "Laptop LT0431 won't boot" and "Laptop LT0899 won't
boot" are the same question, and leaving the asset tags in makes them look
like different ones — exactly the mistake that would hide the biggest
recurring gaps behind a wall of unique-looking subjects.

```php
function writeupSubjectTokens(string $subject): array
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 478.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$subject` | `string` |  | a ticket subject or article title |

**Returns:** `array`

## Calls

[writeupStem()](/code/functions/writeupStem.md), [writeupStopWords()](/code/functions/writeupStopWords.md)

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapAnalyse()](/code/functions/gapAnalyse.md)

