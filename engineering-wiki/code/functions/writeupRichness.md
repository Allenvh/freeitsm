# writeupRichness()

How much of an article could actually be written from this one ticket? 0-100.

⚠️ READ THIS BEFORE TRUSTING THE NUMBER. This is a cheap heuristic for
ORDERING and for deciding whether it's worth spending a model call at all. It
is NOT the judgement of whether an article exists — only the model makes that
call, having read the words. A long ticket can still be pure back-and-forth
about scheduling, and a short one can contain the exact registry key.

The weights encode what actually correlates with a writable ticket on a
service desk: somebody explained something at length (resolution + notes),
there was a diagnosis rather than a single exchange (turns), and it took real
time. Time is the most honest of the four — a two-minute ticket is a password
reset no matter how many words were typed.

```php
function writeupRichness(array $p): int
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 197.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | message_count, notes_len, resolution_len, minutes (ints) and has_problem (bool); missing keys count as 0 |

**Returns:** `int`

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapBulkRichness()](/code/functions/gapBulkRichness.md)
- [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md) - in [writeupTicketBundle()](/code/functions/writeupTicketBundle.md)

