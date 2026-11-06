# projectTargetDoneFields()

The fields a "done" rule can test: column, kind (list = an id from a list, text = free text) and the operators that make sense for it.

```php
function projectTargetDoneFields(): array
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 45.

**Returns:** `array`

## Called from

- [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) - in [projectTargetNormalise()](/code/functions/projectTargetNormalise.md), [projectTargetSql()](/code/functions/projectTargetSql.md), [projectTargetOptions()](/code/functions/projectTargetOptions.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateNormalise()](/code/functions/projectTemplateNormalise.md)

