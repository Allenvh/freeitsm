# projectEnabledTools()

The tools switched on for one project: its method's defaults, then its tailoring.

```php
function projectEnabledTools(array $project): array
```

Defined in [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), line 97.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$project` | `array` |  | the projects row - methodology and tailoring (JSON) are read |

**Returns:** `array`

## Calls

[projectMethodologies()](/code/functions/projectMethodologies.md), [projectToolDefinitions()](/code/functions/projectToolDefinitions.md)

## Called from

- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatSystem()](/code/functions/projectChatSystem.md)
- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectBaselineAuto()](/code/functions/projectBaselineAuto.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectExceptions()](/code/functions/projectExceptions.md), [projectDecorate()](/code/functions/projectDecorate.md)

