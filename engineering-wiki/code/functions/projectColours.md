# projectColours()

The colour a project picks for its card: a fixed palette, never free CSS, so a stored value can only ever be one of these keys.

```php
function projectColours(): array
```

Defined in [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), line 151.

**Returns:** array<string,array{0:string,1:string}> key => [from, to]

## Called from

- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md) - in [projectColourHex()](/code/functions/projectColourHex.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateNormalise()](/code/functions/projectTemplateNormalise.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::fieldMap`

