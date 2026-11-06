# gapSummarySentence()

The assistant's opening line, in plain English.

```php
function gapSummarySentence(int $read, int $days, int $clusters): string
```

Defined in [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), line 550.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$read` | `int` |  | how many closed tickets were read |
| `$days` | `int` |  | the look-back window in days |
| `$clusters` | `int` |  | how many gap clusters were found |

**Returns:** `string`

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapAnalyse()](/code/functions/gapAnalyse.md)

