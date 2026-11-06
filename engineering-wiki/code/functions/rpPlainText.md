# rpPlainText()

Rich text from an incident comment, flattened for a table's detail line.

```php
function rpPlainText(string $html): string
```

Defined in [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md), line 287.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$html` | `string` |  | an incident update's comment as stored (rich text HTML) |

**Returns:** `string`

## Called from

- [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) - in [rpStatusIncidentLog()](/code/functions/rpStatusIncidentLog.md)

