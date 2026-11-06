# apiSerializeProjectItem()

Scope items (deliverables, with MoSCoW)

```php
function apiSerializeProjectItem(array $i): array
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 391.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$i` | `array` |  | a scope item row as ProjectToolsService::items() returns it, with stage_name |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectItemsCreate()](/code/functions/apiProjectItemsCreate.md), [apiProjectItemsUpdate()](/code/functions/apiProjectItemsUpdate.md)

