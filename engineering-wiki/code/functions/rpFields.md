# rpFields()

Drop the empties, so callers can just list every field they might have.

```php
function rpFields(array $fields): array
```

Defined in [includes/record_preview.php](/code/files/includes/record_preview.php.md), line 124.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fields` | `array` |  | the fields from `rpField()`, nulls included - they are dropped |

**Returns:** `array`

## Called from

- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewTicket()](/code/functions/recordPreviewTicket.md), [recordPreviewTask()](/code/functions/recordPreviewTask.md), [recordPreviewChange()](/code/functions/recordPreviewChange.md), [recordPreviewProblem()](/code/functions/recordPreviewProblem.md), [recordPreviewAsset()](/code/functions/recordPreviewAsset.md), [recordPreviewContract()](/code/functions/recordPreviewContract.md), [recordPreviewDomain()](/code/functions/recordPreviewDomain.md)

