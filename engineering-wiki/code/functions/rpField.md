# rpField()

Shorthand for one line of a preview.

```php
function rpField(string $label, $value, ?string $colour = null): ?array
```

Defined in [includes/record_preview.php](/code/files/includes/record_preview.php.md), line 90.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$label` | `string` |  | the field's label as shown |
| `$value` |  |  | the value to show; null or an empty string leaves the field out |
| `$colour` | `?string` | `null` | a status colour for the dot (CSS colour); anything that is not a plain colour is dropped |

**Returns:** `?array`

## Called from

- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewTicket()](/code/functions/recordPreviewTicket.md), [recordPreviewTask()](/code/functions/recordPreviewTask.md), [recordPreviewChange()](/code/functions/recordPreviewChange.md), [recordPreviewProblem()](/code/functions/recordPreviewProblem.md), [recordPreviewAsset()](/code/functions/recordPreviewAsset.md), [recordPreviewContract()](/code/functions/recordPreviewContract.md), [recordPreviewDomain()](/code/functions/recordPreviewDomain.md)

