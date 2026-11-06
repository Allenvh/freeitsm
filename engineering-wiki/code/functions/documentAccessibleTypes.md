# documentAccessibleTypes()

Which entity types this analyst may read at all.

Module membership is the read gate, exactly as the command palette has it:
"Reads aren't capability-gated in FreeITSM, so module membership is the right
gate here." A NULL allowed_modules means unrestricted.

```php
function documentAccessibleTypes(?array $allowedModules): array
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 364.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$allowedModules` | `?array` |  | the analyst's allowed module keys (`$_SESSION`['allowed_modules']); null means all |

**Returns:** `array`

## Calls

[documentEntityRegistry()](/code/functions/documentEntityRegistry.md)

## Called from

- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentVisibilityClause()](/code/functions/documentVisibilityClause.md)

