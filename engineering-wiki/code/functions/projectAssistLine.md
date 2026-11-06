# projectAssistLine()

One project as a single line of text for the assistant - code, name, status, health, priority, progress, target date, manager and why it needs attention.

```php
function projectAssistLine(array $p): string
```

Defined in [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), line 123.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | a project row decorated by `projectDecorate()`, with owner_name |

**Returns:** `string`

## Calls

[projectAssistWhy()](/code/functions/projectAssistWhy.md)

## Called from

- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistList()](/code/functions/projectAssistList.md), [projectAssistOverview()](/code/functions/projectAssistOverview.md)

