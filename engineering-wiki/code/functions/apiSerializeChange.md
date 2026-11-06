# apiSerializeChange()

Summary shape (lists). Detail adds the longtext bodies, PIR, attachments, links.

```php
function apiSerializeChange(array $r): array
```

Defined in [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md), line 86.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the changes row as `apiChangeSelect()` loads it, with the lookup and people names |

**Returns:** `array`

## Calls

[apiChangeNumber()](/code/functions/apiChangeNumber.md), [apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangesGet()](/code/functions/apiChangesGet.md), [apiChangesCreate()](/code/functions/apiChangesCreate.md), [apiChangesUpdate()](/code/functions/apiChangesUpdate.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::fullObjectLoaders`

