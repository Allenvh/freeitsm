# apiSerializeNmDiagram()

Shapes a diagram row for the API - ids, title, version, is_current, author, dates and counts, plus paper and header/footer branding when `$full`.

```php
function apiSerializeNmDiagram(array $r, bool $full): array
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 180.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the diagram row as `apiNmDiagramSelect()` loads it, with author name and counts |
| `$full` | `bool` |  | true to add the paper and branding blocks (single-diagram responses); false for lists |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmDiagramsList()](/code/functions/apiNmDiagramsList.md), [apiNmDiagramsGet()](/code/functions/apiNmDiagramsGet.md), [apiNmRespondFull()](/code/functions/apiNmRespondFull.md), [apiNmVersionsList()](/code/functions/apiNmVersionsList.md)

