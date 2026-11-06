# projectMethodologies()

The presets. 'timebox' is the project_stages.kind used for new and open time boxes; 'single_active' allows only one active time box at a time.

```php
function projectMethodologies(): array
```

Defined in [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), line 60.

**Returns:** array<string,array{label_key:string,desc_key:string,timebox:string,single_active:bool}>

## Called from

- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsList()](/code/functions/apiProjectsList.md)
- [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) - in [projectCreateFromAction()](/code/functions/projectCreateFromAction.md)
- [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md) - in [projectEnabledTools()](/code/functions/projectEnabledTools.md)
- [includes/projects/settings.php](/code/files/includes/projects/settings.php.md) - in [projectSettingValidate()](/code/functions/projectSettingValidate.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateNormalise()](/code/functions/projectTemplateNormalise.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::createFromTemplate`
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::fieldMap`, `ProjectsService::applyMethodology`, `ProjectsService::saveStage`
- [projects/help.php](/code/files/projects/help.php.md)
- [projects/tutorial.php](/code/files/projects/tutorial.php.md)

