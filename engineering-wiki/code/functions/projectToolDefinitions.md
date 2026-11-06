# projectToolDefinitions()

The tools a project can switch on (phase 2). A method turns a set on by default ('tools' in `projectMethodologies()`); a project can TAILOR that - PRINCE2's own "tailor to suit the project" principle - and its choices are stored as JSON in projects.tailoring ({"raci": true, "raid": false}). Switching a tool off HIDES it; nothing it holds is deleted.

```php
function projectToolDefinitions(): array
```

Defined in [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), line 78.

**Returns:** array<string,array{label_key:string,desc_key:string}>

## Called from

- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatSystem()](/code/functions/projectChatSystem.md), [projectChatTools()](/code/functions/projectChatTools.md), [projectChatApplyOne()](/code/functions/projectChatApplyOne.md)
- [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md) - in [projectEnabledTools()](/code/functions/projectEnabledTools.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateNormalise()](/code/functions/projectTemplateNormalise.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::validateField`

