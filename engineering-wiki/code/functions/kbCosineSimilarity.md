# kbCosineSimilarity()

Cosine similarity of two equal-ish length vectors (0 if either is a zero vector).

```php
function kbCosineSimilarity(array $a, array $b): float
```

Defined in [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md), line 28.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` | `array` |  | an embedding vector (list of floats) |
| `$b` | `array` |  | another embedding vector; only the shorter length of the two is compared |

**Returns:** `float`

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapAnalyse()](/code/functions/gapAnalyse.md)
- [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md) - in [kbRetrieveArticles()](/code/functions/kbRetrieveArticles.md)

