# writeupCleanLabel()

Tidy a ticket subject into something that reads as a knowledge-base heading. Cosmetic only — the model writes the real title when it drafts.

```php
function writeupCleanLabel(string $subject): string
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 615.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$subject` | `string` |  | the ticket subject to tidy into a heading |

**Returns:** `string`

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapPersistClusters()](/code/functions/gapPersistClusters.md)

