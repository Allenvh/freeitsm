# projectTemplateNormalise()

Check and tidy template content (from a saved row, the screen or a built-in). Unknown keys are dropped, every list is capped, days are clamped to 0-3650.

```php
function projectTemplateNormalise(array $c): array
```

Defined in [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), line 224.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the template content (untrusted): methodology, stages, tasks, milestones, items, raid, targets, benefits ... |

**Returns:** `array`

## Calls

[projectColours()](/code/functions/projectColours.md), [projectIcons()](/code/functions/projectIcons.md), [projectMethodologies()](/code/functions/projectMethodologies.md), [projectTargetDoneFields()](/code/functions/projectTargetDoneFields.md), [projectTargetScopeFields()](/code/functions/projectTargetScopeFields.md), [projectToolDefinitions()](/code/functions/projectToolDefinitions.md)

## Called from

- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateList()](/code/functions/projectTemplateList.md), [projectTemplateLoad()](/code/functions/projectTemplateLoad.md), [projectTemplateCapture()](/code/functions/projectTemplateCapture.md)

