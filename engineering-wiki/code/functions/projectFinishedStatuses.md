# projectFinishedStatuses()

The project statuses that count as finished: closed and cancelled.

```php
function projectFinishedStatuses(): array
```

Defined in [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), line 122.

**Returns:** `array`

## Called from

- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectAutoHealth()](/code/functions/projectAutoHealth.md), [projectExceptions()](/code/functions/projectExceptions.md), [projectDecorate()](/code/functions/projectDecorate.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::createProject`, `ProjectsService::updateProject`

