# writeupClusterUserMessage()

Build the user message for a cluster write-up: the richest ticket as the source, the rest as evidence of how the question gets asked.

```php
function writeupClusterUserMessage(
    array $bundle,
    array $otherSubjects,
    int $count,
    string $answers = ''
): string
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 773.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$bundle` | `array` |  | the bundle of the cluster's richest ticket, from `writeupTicketBundle()` |
| `$otherSubjects` | `array` |  | the subjects of the other tickets in the cluster; the first 15 non-blank are sent |
| `$count` | `int` |  | how many tickets are in the cluster |
| `$answers` | `string` | `''` | the analyst's answers to the model's questions; '' for a first attempt |

**Returns:** `string`

## Called from

- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)

