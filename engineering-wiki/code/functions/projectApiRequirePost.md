# projectApiRequirePost()

Writes are POST only, so a link or an image tag can never change a project.

```php
function projectApiRequirePost(): void
```

Defined in [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), line 67.

**Returns:** `void`

## Calls

[projectApiFail()](/code/functions/projectApiFail.md)

## Called from

- [api/projects/delete.php](/code/files/api/projects/delete.php.md)
- [api/projects/save.php](/code/files/api/projects/save.php.md)
- [api/projects/stage_delete.php](/code/files/api/projects/stage_delete.php.md)
- [api/projects/stage_reorder.php](/code/files/api/projects/stage_reorder.php.md)
- [api/projects/stage_save.php](/code/files/api/projects/stage_save.php.md)
- [api/projects/task_assign.php](/code/files/api/projects/task_assign.php.md)
- [api/projects/task_create.php](/code/files/api/projects/task_create.php.md)

