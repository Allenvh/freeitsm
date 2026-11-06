# apiSerializeLicence()

Shapes a licence row for the API, adding renewal_status - overdue, due_soon (inside the notice period, 30 days by default) or ok - computed the way the licence screen colours it.

```php
function apiSerializeLicence(array $r): array
```

Defined in [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md), line 245.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the software_licences row as `apiSoftwareLicenceSelect()` loads it, with app name, publisher, installs and creator |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) - in [apiSoftwareLicencesGet()](/code/functions/apiSoftwareLicencesGet.md), [apiSoftwareLicencesCreate()](/code/functions/apiSoftwareLicencesCreate.md), [apiSoftwareLicencesUpdate()](/code/functions/apiSoftwareLicencesUpdate.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::fullObjectLoaders`

