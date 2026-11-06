# getDebugTools()

Every Debug Tool with its card and page metadata - id, slug, backend file, title, category, description, search keywords, when to run it, what it checks.

```php
function getDebugTools()
```

Defined in [system/debug-tools/includes/tools.php](/code/files/system/debug-tools/includes/tools.php.md), line 25.

**Returns:** array<int,array<string,mixed>> Ordered list of debug tools.

## Calls

[debugToolAiFeatureOptions()](/code/functions/debugToolAiFeatureOptions.md), [debugToolAnalystOptions()](/code/functions/debugToolAnalystOptions.md)

## Called from

- [system/debug-tools/includes/tools.php](/code/files/system/debug-tools/includes/tools.php.md) - in [getDebugToolBySlug()](/code/functions/getDebugToolBySlug.md)
- [system/debug-tools/index.php](/code/files/system/debug-tools/index.php.md)

