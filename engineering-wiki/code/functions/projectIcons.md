# projectIcons()

The icons a project can pick. Keys only - the SVG lives in assets/js/projects.js so the stored value is never markup.

```php
function projectIcons(): array
```

Defined in [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), line 171.

**Returns:** `array`

## Called from

- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateNormalise()](/code/functions/projectTemplateNormalise.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::fieldMap`

